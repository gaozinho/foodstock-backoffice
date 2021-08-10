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
        'product.category_id' => 'required|integer',
        'product.name' => 'max:255|required',
        'product.description' => 'max:500|nullable',
        'product.minimun_stock' => 'required|integer|min:0',
        'product.current_stock' => 'required|integer',
        'product.monitor_stock' => 'required|boolean',
        'product.external_code' => 'max:50|nullable',
        'product.unit' => 'max:10|required',
        'product.ean' => 'max:255|nullable',
        'product.unit_price' => 'required|numeric|min:0',
        'product.index' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'product.serving' => 'max:20|nullable',
        'product.enabled' => 'required|boolean',
        'product.initial_step' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'product.category_id.required' => 'Escolha a categoria.',
        'product.unit.required' => 'Escolha a unidade.',
        'product.unit_price.required' => 'Informe o preço.',
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

        $products = Product::where("restaurant_id", $this->restaurant->id)
            ->where("deleted", 0)
            ->where("parent_id", null);

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
            $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            $this->product->restaurant_id = $restaurant->id;
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
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $this->resetValidation();
        $this->loadBaseData();
        $this->product = Product::where("restaurant_id", $restaurant->id)->where("id", $id)->firstOrFail();
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
        
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $this->categories = Category::where("restaurant_id", $restaurant->id)->orWhere("restaurant_id", null)
            ->where("enabled", 1)
            ->orderBy("name")
            ->select("id", "name")
            ->get()->pluck("name", "id")->toArray();
        $this->productionLines = ProductionLine::where("restaurant_id", $restaurant->id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step")
            ->select("step", "name")
            ->pluck("name", "step")->toArray();

    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
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
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
        $product = Product::where("restaurant_id", $restaurant->id)->where("id", $this->product->id)->firstOrFail();
        $product->deleted = 1;
        $product->save();
        $this->render();
        $this->simpleAlert('success', 'Produto excluído com sucesso.');
    }

    public function confirmDestroy($id)
    {
        $this->product = Product::findOrFail($id);
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
