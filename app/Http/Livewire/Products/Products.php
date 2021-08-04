<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Products extends Component
{
    use WithPagination;
	use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, 
        $sort = "name", $direction = "";
        public $saveMode = false;
        public $pageSize = 20;

	public Product $product;

    protected $rules = [
        /*
        'product.nome' => 'required|string|min:1|max:255',
        'product.descricao' => 'max:1000|required',
        'product.data_inicio' => 'required|date_format:d/m/Y|nullable',
        'product.data_fim' => 'required|date_format:d/m/Y|nullable',
        'product.link' => 'string|min:1|nullable',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'product.is_active' => 'boolean|nullable', 
        */
        'product.category_id' => 'required|integer',
        'product.name' => 'max:255|required',
        'product.description' => 'max:500|required',
        'product.minimun_stock' => 'required|integer',
        'product.current_stock' => 'required|integer',
        'product.monitor_stock' => 'required|boolean',
        'product.external_code' => 'max:50|nullable',
        'product.unit' => 'max:10|required',
        'product.ean' => 'max:255|nullable',
        'product.unit_price' => 'required|numeric',
        'product.index' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'product.serving' => 'max:20|nullable',
        'product.enabled' => 'required|boolean',
        'product.deleted' => 'required|boolean',
        'product.initial_step' => 'required|numeric',
    ];

    public function updatingKeyWord($value){
        $this->emit('tableUpdating');
        $this->resetPage();
    }

    public function updatedKeyWord($value){
        $this->emit('paginationLoaded');
    }    

    public function mount(){
        $this->sort = request()->query('sort');
        $this->direction = request()->query('direction');
    }

    public function render()
    {
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);

        //$this->dispatchBrowserEvent('gotoTop');
		$keyWord = '%'.$this->keyWord .'%';
        $this->emit('paginationLoaded');

        $products = Product::where("restaurant_id", $restaurant->id)
        ->where("deleted", 0)
        ->where("parent_id", null);

        if(!empty($this->keyWord)){
            $products->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('description', 'LIKE', $keyWord);
        }

        if(!empty($this->sort)) $products->orderBy($this->sort, !empty($this->direction) ? $this->direction : "ASC");
        
        $products->orderBy("name", "ASC");

        return view('livewire.products.view', [
            'products' => $products->paginate($this->pageSize),
        ]);
    }

    public function sort($column)
    {
        $this->sort = $column;
        $this->direction = $this->direction == "ASC" ? "DESC" : "ASC";

        $this->queryString = ["sort" => $this->sort, "direction" => $this->direction];

        $this->emit('paginationLoaded');
    }    
	
    public function cancel()
    {
        $this->resetInput();
        $this->saveMode = false;
    }
	
    private function resetInput()
    {		
		$this->imagem = null;
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
        $this->product = new Product();
		$this->saveMode = true;
    }

    public function store()
    {
        try {
			$this->validate();
			is_object($this->imagem) ? $this->product->imagem = $this->imagem->store('products', 'public') : null;
            $this->product->save();
			session()->flash('success', 'Product salvo com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->product = Product::findOrFail($id);
		$this->nome = $this->product->nome;
		$this->saveMode = true;
    }

    public function update($continue)
    {
        try {
			$this->validate();

            if(is_object($this->imagem)){
                $this->product->imagem = $this->imagem->store('products', 'public');
            }

            $this->product->save();
			session()->flash('success', 'Product salvo com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Product.');
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            Storage::delete($product->imagem);
            $product->delete();
			session()->flash('success', 'Product excluído com sucesso.');
        }catch(\Illuminate\Database\QueryException $e) {
            $mensagem = $this->formatSqlError($e->getPrevious()->getErrorCode(), $e->getMessage());
			session()->flash('error', sprintf('Não foi possível excluir o registro. <br />%s', $mensagem));
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Unexpected error occurred while trying to process your request.');
        }
    } 

    public function report() 
    {
        $fileName = sprintf("Products %s.xlsx", date("d-m-Y"));
        return (new ProductsExport)->filtro($this->keyWord)->download($fileName);
    }
    
    public function removeImagem(){
        Storage::delete("public/" . $this->product->imagem);
        $this->product->imagem = "";
    }    

    public function downloadImagem(){
		return Storage::download("public/" . $this->product->imagem, ($this->product->nome . "." . pathinfo($this->product->imagem, PATHINFO_EXTENSION)));
    }       
}
