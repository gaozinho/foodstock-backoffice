<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductionLine;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class Products extends BaseConfigurationComponent
{
    use WithPagination;
	use WithFileUploads;

    protected $listeners = [
        'disable'
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

    protected $rules = [
        'product.category_id' => 'nullable|integer',
        'product.name' => 'max:255|required',
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
    ];

    protected $messages = [
        'product.category_id.required' => 'Escolha a categoria.',
        'product.unit.required' => 'Escolha a unidade.',
        'product.unit_price.required' => 'Informe o preço.',
        'product.initial_step.required' => 'Informe para onde este produto leva sua produção.',
    ];

    //Ordena pela coluna escolhida
    public function sort($column)
    {
        $this->sort = $column;
        $this->direction = $this->direction == "ASC" ? "DESC" : "ASC";
        $this->queryString = array_merge($this->queryString, ["sort" => $this->sort, "direction" => $this->direction]);
        $this->emit('paginationLoaded');
    }   

    public function mount(){
        $this->restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        $this->sort = request()->query('sort');
        $this->direction = request()->query('direction');
    }

    public function render()
    {
        //$restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        //$this->dispatchBrowserEvent('gotoTop');
		$keyWord = '%'.$this->keyWord .'%';
        $this->emit('paginationLoaded');

        $products = Product::where("user_id", auth()->user()->id)
            ->where("deleted", 0);
            //->where("parent_id", null)

        if(!empty($this->keyWord)){
            $products->where(function($query) use ($keyWord){
                $query->orWhere('name', 'LIKE', $keyWord)
                    ->orWhere('description', 'LIKE', $keyWord);
            });
        }

        if($this->monitor_stock == 1 || request()->monitor_stock == 1){
            $products->where('monitor_stock', 1);
        }
        
        if($this->enabled == 1 || request()->enabled == 1){
            $products->where('enabled', 0);
        }  

        if($this->stock_alert == 1 || request()->stock_alert == 1){
            $products->whereRaw('current_stock <= minimun_stock');
        }
        
        if($this->stock_zero == 1 || request()->stock_zero == 1){
            $products->whereRaw('current_stock <= 0');
        }          

        if(!empty($this->sort)){
            $products->orderBy($this->sort, !empty($this->direction) ? $this->direction : "ASC");
        }else{
            $products->orderBy("name", "ASC");
        }      

        return view('livewire.products.view', [
            'products' => $products->paginate($this->pageSize),
        ]);
    }
	
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

        $this->product->category_id = empty($this->product->category_id) ? null : $this->product->category_id;
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
            //$restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            //$this->product->restaurant_id = $restaurant->id;
            $this->product->user_id = auth()->user()->id;
			$this->validate();
			is_object($this->image) ? $this->product->image = $this->image->store('products', 'public') : null;
            $this->product->save();
			//session()->flash('success', 'Produto salvo com sucesso.');
            $this->simpleAlert('success', 'Produto salvo com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }

    public function edit($id)
    {
        //$restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $this->resetValidation();
        $this->loadBaseData();
        $this->product = Product::where("user_id", auth()->user()->id)->where("id", $id)->firstOrFail();
		$this->nome = $this->product->nome;
		$this->saveMode = true;
    }

    public function update($continue)
    {
        try {
			$this->validate();
            if(is_object($this->image)){
                $this->product->image = $this->image->store('products', 'public');
            }

            $this->product->save();
			//session()->flash('success', 'Produto atualizado com sucesso.');
            $this->simpleAlert('success', 'Produto atualizado com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }

    public function loadBaseData(){
        
        //$restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $this->categories = Category::where("user_id", auth()->user()->id)
            ->orWhere("user_id", null)
            ->where("enabled", 1)
            ->orderBy("name")
            ->select("id", "name")
            ->get()->pluck("name", "id")->toArray();
        $this->productionLines = ProductionLine::where("user_id", auth()->user()->id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step")
            ->select("step", "name")
            ->pluck("name", "step")->toArray();

    }

    public function destroy($id)
    {
        try {
            $product = Product::where("user_id", auth()->user()->id)->where("id", $id)->firstOrFail();
            Storage::delete($product->image);
            $product->delete();
			session()->flash('success', 'Produto excluído com sucesso.');
        }catch(\Illuminate\Database\QueryException $e) {
            $mensagem = $this->formatSqlError($e->getPrevious()->getErrorCode(), $e->getMessage());
			session()->flash('error', sprintf('Não foi possível excluir o registro. <br />%s', $mensagem));
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Unexpected error occurred while trying to process your request.');
        }
    }

    public function disable(){
        //$restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $product = Product::where("user_id", auth()->user()->id)->where("id", $this->product->id)->firstOrFail();
        $product->deleted = 1;
        $product->minimun_stock = 0;
        $product->current_stock = 0;
        $product->monitor_stock = 0;
        $product->save();
        $this->render();
        $this->simpleAlert('success', 'Produto excluído com sucesso.');
    }

    public function confirmDestroy($id)
    {
        $this->product = Product::where("user_id", auth()->user()->id)->where("id", $id)->firstOrFail();
        $this->confirm('Deseja excluir ' . $this->product->name . '?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
            'onConfirmed' => 'disable'
        ]);
    }

    public function report() 
    {
        $fileName = sprintf("Products %s.xlsx", date("d-m-Y"));
        return (new ProductsExport)->filtro($this->keyWord)->download($fileName);
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

    // SEARCH
    public function updatingKeyWord($value){
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedKeyWord($value){
        $this->emit('paginationLoaded');
    }  
    
    public function updatingMonitorStock($value){
        $this->addToQuerystring(["monitor_stock" => $value]);
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedMonitorStock($value){
        $this->emit('paginationLoaded');
    }  
    
    public function updatingEnabled($value){
        $this->addToQuerystring(["enabled" => $value]);
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedEnabled($value){
        $this->emit('paginationLoaded');
    }  

    public function updatingStockAlert($value){
        $this->addToQuerystring(["stock_alert" => $value]);
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedStockAlert($value){
        $this->emit('paginationLoaded');
    }   
    
    public function updatingStockZero($value){
        $this->addToQuerystring(["stock_zero" => $value]);
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedStockZero($value){
        $this->emit('paginationLoaded');
    }

    
    protected function prepareForValidation($attributes)
    {
        $attributes["product"]->unit_price = floatval(str_replace(',', '.', $attributes["product"]->unit_price));
        return $attributes;
    }    
}
