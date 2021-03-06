<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductionLine;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;

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
    public $restaurant_ids = [];
    public $productModels;
    public $user_id;
    public $check;



    public $formatedQueryString;

    protected $rules = [
        'productModels.*.initial_step' => 'nullable',
        'productModels.*.monitor_stock' => 'nullable',
    ];

    // PAGINAÇÃO ######################
    public function sort($column)
    {
        $this->sort = $column;
       
        $this->direction = $this->direction == "ASC" ? "DESC" : "ASC";
        $this->queryString = array_merge($this->queryString, ["sort" => $this->sort, "direction" => $this->direction]);
        $this->emit('paginationLoaded');
    }   

    public function mount($id = 0){
        $this->formatedQueryString = $this->formatInitialQueryString();
        if(intval($id) > 0) $this->edit($id);
        $this->sort = request()->query('sort');
        $this->direction = request()->query('direction');
        $this->keyWord = request()->query('keyWord');
        $this->loadBaseData();
    }

    public function render()
    {

        
        $this->user_id = auth()->user()->user_id ?? auth()->user()->id;
        
		$keyWord = '%'.$this->keyWord .'%';
        $this->emit('paginationLoaded');

        $products = Product::leftJoin("items", "products.id", "=", "items.product_id")
            ->where("products.deleted", 0)
            ->where("products.user_id", $this->user_id)
            ->selectRaw("distinct products.*")
            ->distinct("products.id");
        
        if(!empty($this->keyWord)){
            $products->where(function($query) use ($keyWord){
                $query->orWhere('products.name', 'LIKE', $keyWord)
                ->orWhere('products.description', 'LIKE', $keyWord)
                ->orWhere('products.foodstock_name', 'LIKE', $keyWord)
                ->orWhere('products.external_code', 'LIKE', $keyWord);
            });
        }

        if($this->monitor_stock == 1 || request()->monitor_stock == 1){
            $products->where('products.monitor_stock', 1);
        }
        
        if($this->enabled == 1 || request()->enabled == 1){
            $products->where('products.enabled', 0);
            
        }  

        if($this->stock_alert == 1 || request()->stock_alert == 1){
            $products->whereRaw('products.current_stock <= products.minimun_stock');
            $this->queryString["stock_alert"] = 1;
        }
        
        if($this->stock_zero == 1 || request()->stock_zero == 1){
            $products->whereRaw('products.current_stock <= 0');
            $this->queryString["stock_zero"] = 1;
        }

        if(!empty($this->sort)){
            $products->orderBy($this->sort, !empty($this->direction) ? $this->direction : "ASC");
        }else{
            $products->orderBy("products.name", "ASC");
        }

        $pagination = $products->paginate($this->pageSize);
        $this->productModels = $pagination->items();

        //$this->formatedQueryString = $this->formatInitialQueryString();

        return view('livewire.products.view', [
            'products' => $pagination,
        ]);
    }

    public function formatInitialQueryString(){
        $queryString = [];
        if(request()->enabled == "1") $queryString["enabled"] = $this->enabled = request()->enabled;
        if(request()->stock_alert == "1") $queryString["stock_alert"] = $this->stock_alert = request()->stock_alert;
        if(request()->stock_zero == "1") $queryString["stock_zero"] = $this->stock_zero = request()->stock_zero;
        if(strlen(request()->keyWord) > 0) $queryString["keyWord"] = $this->keyWord = request()->keyWord;
        if(request()->monitor_stock == "1") $queryString["monitor_stock"] = $this->monitor_stock = request()->monitor_stock;   
        if(strlen(request()->sort) > 0) $queryString["sort"] = $this->sort = request()->sort;   
        if(strlen(request()->direction) > 0) $queryString["direction"] = $this->direction = request()->direction;   
        return $queryString; 
        //return http_build_query($this->queryString,'?','&');
    }

    public function updatedProductModels($value, $index){
        $keyProperty = explode(".", $index);
        $productArray = $this->productModels[$keyProperty[0]];
        $product = Product::findOrFail($productArray["id"]);
        $product->{$keyProperty[1]} = intval($value);
        $product->save();
    }

    // FILTROS DA PAGINAÇÃO ##############
    public function updatingKeyWord($value){
        $this->addToQuerystring(["keyWord" => $value]);
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

    public function destroy($id)
    {
        try {
            $product = Product::where("id", $id)->where("user_id", $this->user_id)->firstOrFail();
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
        $product = Product::where("id", $this->product->id)->firstOrFail();
        $product->deleted = 1;
        $product->name = $product->name . ' (APAGADO)';
        $product->minimun_stock = 0;
        $product->current_stock = 0;
        $product->monitor_stock = 0;
        $product->external_code = null;
        $product->save();
        $this->render();
        $this->simpleAlert('success', 'Produto excluído com sucesso.');
    }

    public function confirmDestroy($id)
    {
        $this->product = Product::where("id", $id)->firstOrFail();
        $this->confirm('Deseja excluir ' . ($this->product->foodstock_name ?? $this->product->name) . '?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
            'onConfirmed' => 'disable'
        ]);
    } 

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }   

    // UTILIDADES ############


    public function loadBaseData(){
        $this->user_id = auth()->user()->user_id ?? auth()->user()->id;
        $this->productionLines = ProductionLine::where("user_id", $this->user_id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step")
            ->select("step", "name")
            ->pluck("name", "step")->toArray();
    }    

    public function report() 
    {
        $fileName = sprintf("Products %s.xlsx", date("d-m-Y"));
        return (new ProductsExport)->filtro($this->keyWord)->download($fileName);
    }


 
}
