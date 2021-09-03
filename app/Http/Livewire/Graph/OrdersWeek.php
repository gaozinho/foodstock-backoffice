<?php

namespace App\Http\Livewire\Graph;

use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

use App\Models\FederatedSale;
use App\Models\FederatedDayOrder;
use Carbon\Carbon;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class OrdersWeek extends Component
{

    protected $listeners = ['render_orders' => 'render'];
    public $selectedRestaurants;

    public function mount(){
        $this->selectedRestaurants = session('selectedRestaurants');
    }

    public function render()
    {
        $weekMap = [0 => 'DOM', 1 => 'SEG', 2 => 'TER', 3 => 'QUA', 4 => 'QUI', 5 => 'SEX', 6 => 'SAB'];
        
        $restaurant_ids = is_array($this->selectedRestaurants) ? $this->selectedRestaurants : (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();

        $lineChartModel = FederatedDayOrder::whereIn("restaurant_id", $restaurant_ids)
            ->whereBetween("date", [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()])
            ->groupBy("date")
            ->orderBy("date")
            ->select("federated_day_orders.date")
            ->selectRaw("SUM(federated_day_orders.total) as total")            
            ->get()
            ->reduce(function ($lineChartModel, $data) use ($weekMap){
                    $date = Carbon::createFromFormat('Y-m-d', $data->date)->dayOfWeek;
                    return $lineChartModel->addSeriesPoint("Semana atual", $weekMap[$date], $data->total);
                }, LivewireCharts::lineChartModel()
                    ->setTitle('Semana atual X Semana anterior')
                    ->multiLine()
                    //->setSmoothCurve()
                    ->setAnimated(true)
            );

        FederatedDayOrder::whereIn("restaurant_id", $restaurant_ids)
            ->whereBetween("date", [Carbon::now()->subDays(13)->toDateString(), Carbon::now()->subDays(7)->toDateString()])
            ->groupBy("date")
            ->orderBy("date")
            ->select("federated_day_orders.date")
            ->selectRaw("SUM(federated_day_orders.total) as total")            
            ->get()
            ->reduce(function ($previousWeek, $data) use ($lineChartModel, $weekMap){
                $date = Carbon::createFromFormat('Y-m-d', $data->date)->dayOfWeek;
                return $lineChartModel->addSeriesPoint("Semana anterior", $weekMap[$date], $data->total);
            }
        );

        return view('livewire.graph.orders-week')->with(["lineChartModel" => $lineChartModel]);
    }
}
