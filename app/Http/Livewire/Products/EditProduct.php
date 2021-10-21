<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductionLine;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use  App\Actions\Help\PerformHealthCheck;
//use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Actions\ProductionLine\RecoverUserRestaurant;
//use App\Integrations\IfoodIntegrationDistributed;
//use App\Actions\Product\Ifood\BrokerProducts;
use App\Jobs\ProcessIfoodItems;
use Illuminate\Support\Facades\Cache;
use App\Models\StockPanel;

class EditProduct extends BaseConfigurationComponent
{
    use WithPagination;
	use WithFileUploads;

    protected $listeners = [
        'updatePanel'
    ];

	protected $paginationTheme = 'bootstrap';
    public $keyWord, $sort = "name", $direction = "", $image = null, $monitor_stock = 0, $enabled = 0, $stock_alert = 0, $stock_zero = 0;
    public $saveMode = false;
    public $pageSize = 20;

    //Model da página
	public Product $product;

    //Preencher página com dados
    public $categories;
    public $productionLines;
    public $restaurant;
    public $restaurant_ids = [];
    public $productModels;
    public $user_id;
    public $check;

    //Job de importação de cardápio
    public $importIfoodRunning = false;

    public $stock_panels;
    public $stock_panels_select = [];
    

    protected $rules = [
        //'product.category_id' => 'nullable|integer',
        'product.name' => 'max:255|required',
        'product.foodstock_name' => 'min:4|max:255',
        'product.description' => 'max:500|nullable',
        'product.minimun_stock' => 'required|integer|min:0',
        'product.current_stock' => 'required|integer',
        'product.monitor_stock' => 'required|boolean',
        'product.external_code' => 'max:50|nullable',
        'product.unit' => 'max:10|nullable',
        'product.ean' => 'max:255|nullable',
        'product.unit_price' => 'nullable|numeric|min:0',
        'product.index' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'product.serving' => 'max:20|nullable',
        'product.enabled' => 'required|boolean',
        'product.initial_step' => 'nullable|numeric|min:0',
        'productModels.*.initial_step' => 'nullable',
        'productModels.*.monitor_stock' => 'nullable',
        'stock_panels_select.*' => 'numeric'
        
    ];

    protected $messages = [
        //'product.category_id.required' => 'Escolha a categoria.',
        'product.unit.required' => 'Escolha a unidade.',
        'product.unit_price.required' => 'Informe o preço.',
        'product.initial_step.required' => 'Informe para onde este produto leva sua produção.',
    ];

    public function mount($id = 0){
        $this->user_id = auth()->user()->user_id ?? auth()->user()->id;
        if(intval($id) == 0) $this->create();
        else $this->edit(intval($id));
    }

    public function render()
    {
        $user_id = $this->user_id;
        $this->stock_panels = StockPanel::where(function($query) use ($user_id){
            $query->where("user_id", $user_id)
                ->orWhere("user_id", null);
        })->orderBy("name")->get();
        return view('livewire.products.edit');
    }

    public function updatePanel($data){
        $this->stock_panels_select = $data;
    }

    // EDIÇÃO ####################
	
    public function cancel()
    {
        $this->resetInput();
        $this->saveMode = false;
    }
	
    private function resetInput()
    {		
		$this->image = null;
		$this->product = new Product();
    }

    public function save($continue)
    {

        $this->product->unit = empty($this->product->unit) ? null : $this->product->unit;
        $this->product->initial_step = intval($this->product->initial_step) == 0 ? 0 : intval($this->product->initial_step);
        $this->product->unit_price = intval($this->product->unit_price) == 0 ? null : $this->product->unit_price;

		if(intval($this->product->id) > 0){
			$this->update($continue);
		}else{
			$this->store($continue);
		}

        $this->resetInput();
        $this->saveMode = false;
	}

    public function create()
    {
        $this->resetValidation();
        $this->loadBaseData();
        $this->product = new Product();
        $this->product->enabled = 0;
        $this->product->monitor_stock = 0;
        $this->product->minimun_stock = 0;
        $this->product->current_stock = 0;
        $this->product->unit_price = 0;
        $this->product->initial_step = 1;
		$this->saveMode = true;
    }

    public function store()
    {
        try {
            $this->product->user_id = $this->user_id;
			$this->validate();
			is_object($this->image) ? $this->product->image = $this->image->store('products', 'public') : null;
            $this->product->save();
			session()->flash('success', 'Produto salvo com sucesso.');
            redirect()->route('products.index');
            
        } catch (Exception $exception) {
            //if(env('APP_DEBUG')) throw $exception;
            //session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
            $this->simpleAlert('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->loadBaseData();
        $this->product = Product::where("id", $id)->where("user_id", $this->user_id)->firstOrFail();

        $this->stock_panels_select = $this->product->stockPanels()->get()->pluck("id")->toArray();

		$this->nome = $this->product->nome;
    }

    public function update($continue)
    {
        try {
			$this->validate();
            if(is_object($this->image)){
                $this->product->image = $this->image->store('products', 'public');
            }

            $this->product->save();
            $this->product->stockPanels()->sync($this->stock_panels_select);

			session()->flash('success', 'Produto atualizado com sucesso.');
            redirect()->route('products.index');
            //$this->simpleAlert('success', 'Produto atualizado com sucesso.');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }
    
    public function removeImagem(){
        Storage::delete("public/" . $this->product->image);
        $this->product->image = "";
    }    

    public function downloadImagem(){
		return Storage::download("public/" . $this->product->image, ($this->product->name . "." . pathinfo($this->product->image, PATHINFO_EXTENSION)));
    }       

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }   
    
    protected function prepareForValidation($attributes)
    {
        $attributes["product"]->unit_price = floatval(str_replace(',', '.', $attributes["product"]->unit_price));
        return $attributes;
    }  

    public function loadBaseData(){
        $this->productionLines = ProductionLine::where("user_id", $this->user_id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step")
            ->select("step", "name")
            ->pluck("name", "step")->toArray();
    }
}
