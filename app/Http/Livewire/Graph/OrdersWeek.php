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

    public function mount(){

    }

    public function render()
    {
        $weekMap = [0 => 'DOM', 1 => 'SEG', 2 => 'TER', 3 => 'QUA', 4 => 'QUI', 5 => 'SEX', 6 => 'SAB'];
        
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);

        $lineChartModel = FederatedDayOrder::where("restaurant_id", $restaurant->id)
            ->whereBetween("date", [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()])
            ->orderBy("date")
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

        FederatedDayOrder::where("restaurant_id", $restaurant->id)
            ->whereBetween("date", [Carbon::now()->subDays(13)->toDateString(), Carbon::now()->subDays(7)->toDateString()])
            ->orderBy("date")
            ->get()
            ->reduce(function ($previousWeek, $data) use ($lineChartModel, $weekMap){
                $date = Carbon::createFromFormat('Y-m-d', $data->date)->dayOfWeek;
                return $lineChartModel->addSeriesPoint("Semana anterior", $weekMap[$date], $data->total);
            }
        );

        return view('livewire.graph.orders-week')->with(["lineChartModel" => $lineChartModel]);
    }
}
