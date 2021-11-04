<?php

namespace App\Http\Livewire\Indoor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductionLine;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Enums\BrokerType;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Actions\ProductionLine\StartProductionProccess;

use App\Foodstock\Babel\ItemBabel;

class Orders extends BaseConfigurationComponent
{
    use WithPagination;
	use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $keyWord, $sort = "name", $direction = "";
    public $pageSize = 20;

    //Preencher página com dados
    public $productionLines;
    public $productModels;
    public $user_id;

    public $formatedQueryString;
    public $orderProducts = [];
    public $restaurants;

    //Formulário
    public $initial_step;
    public $friendly_number;
    public $restaurant_id;
    public $customer_name;
    public $address;

    protected $rules = [
        'friendly_number' => 'nullable|min:4|max:6|regex:/^[a-zA-Z0-9\s]+$/',
        'initial_step' => 'min:0|max:10|numeric',
        'restaurant_id' => 'min:1|required|numeric',
        'customer_name' => 'nullable',
        'address' => 'nullable'
    ];    

    protected $messages = [
        'friendly_number.*' => 'O número do pedido deve ter entre 4 e 6 caracteres e apenas números e letras.',
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

        if(!empty($this->sort)){
            $products->orderBy($this->sort, !empty($this->direction) ? $this->direction : "ASC");
        }else{
            $products->orderBy("products.name", "ASC");
        }

        $pagination = $products->paginate($this->pageSize);
        $this->productModels = $pagination->items();

        //$this->formatedQueryString = $this->formatInitialQueryString();

        return view('livewire.indoor.view', [
            'products' => $pagination,
        ]);
    }

    public function addProduct($product_id){
        $product = Product::find($product_id)->toArray();
        if(array_key_exists($product_id, $this->orderProducts)){
            $this->orderProducts[$product_id] = ["quantity" => ++$this->orderProducts[$product_id]["quantity"], 'product' => $product];
        }else{
            $this->orderProducts[$product_id] = ["quantity" => 1, 'product' => $product];
        }

    }

    public function removeProduct($product_id){
        if(array_key_exists($product_id, $this->orderProducts)){
            unset($this->orderProducts[$product_id]);
        }
    }

    public function saveOrder(){
        $this->validate();

        $order = $this->createEmptyOrderArray();
        foreach($this->orderProducts as $orderProduct){
            $order["subtotal"] += $orderProduct["product"]["unit_price"] * $orderProduct["quantity"];
            $order["items"][] = (new ItemBabel($orderProduct["product"]["name"], $orderProduct["quantity"], $orderProduct["product"]["unit_price"], ($orderProduct["product"]["unit_price"] * $orderProduct["quantity"]), $orderProduct["product"]["external_code"], ''))->toArray();
        }
        $order["orderAmount"] = $order["subtotal"] + $order["deliveryFee"];
        $order["order_id"] = $order["shortOrderNumber"] . $this->restaurant_id;
        $order["broker_id"] = BrokerType::FoodStock;
        $order["customerName"] =  $this->customer_name;
        $order["deliveryFormattedAddress"] = $this->address;
        if(!empty($this->friendly_number)){
            $order["shortOrderNumber"] = strtoupper($this->friendly_number);
        }

        $json = json_encode($order);
        $orderModel = Order::create(["restaurant_id" => $this->restaurant_id, "broker_id" => BrokerType::FoodStock, "order_id" => null, "json" => $json]);
        $orderModel->order_id = "foodstock-" . $orderModel->id;
        $orderModel->save();

        $this->clearData();

        (new StartProductionProccess())->start($orderModel->id, intval($this->initial_step));
        $message = 'Pedido ' . str_pad($order["shortOrderNumber"], 4, "0", STR_PAD_LEFT) . " registrado com sucesso!";
        session()->flash('success', $message);
        $this->simpleAlert('success', $message, 5000);

    }

    private function clearData(){
        $this->orderProducts = [];
        $this->friendly_number = "";
        $this->customer_name = "";
        $this->address = "";   
    }

    private function createEmptyOrderArray(){
        return ["items" => [], "subtotal" => 0, "deliveryFee" => 0, "orderAmount" => 0, "shortOrderNumber" => rand(1, 9999), "createdDate" => date("Y-m-d H:i:s"), 
        "ordersCountOnMerchant" => 0, "customerName" => "N/A", "deliveryFormattedAddress" => "N/A", "brokerId" => "", "brokerName" => "foodStock", "orderType" => "INDOOR",
        "benefits" => [], "additionalFees" => 0, "benefitsTotal" => 0, "payments" => ["pending" => 0, "prepaid" => 0, "methods" => [], ], "schedule" => false];
    }

    public function formatInitialQueryString(){
        $queryString = [];
        if(strlen(request()->keyWord) > 0) $queryString["keyWord"] = $this->keyWord = request()->keyWord;
        if(strlen(request()->sort) > 0) $queryString["sort"] = $this->sort = request()->sort;   
        if(strlen(request()->direction) > 0) $queryString["direction"] = $this->direction = request()->direction;   
        return $queryString; 
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

    // UTILIDADES ############

    public function loadBaseData(){
        $this->user_id = auth()->user()->user_id ?? auth()->user()->id;
        $this->productionLines = ProductionLine::where("user_id", $this->user_id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step")
            ->select("step", "name")
            ->pluck("name", "step")->toArray();
        $this->restaurants = (new RecoverUserRestaurant())->recoverAll($this->user_id)->pluck("name", "id")->toArray();
        $this->restaurant_id = array_keys($this->restaurants)[0];
        $this->initial_step = 0;
    }    
}
