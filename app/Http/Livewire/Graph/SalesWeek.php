<?php

namespace App\Http\Livewire\Graph;

use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

use App\Models\FederatedSale;
use App\Models\FederatedDaySale;
use Carbon\Carbon;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class SalesWeek extends Component
{

    protected $listeners = ['render_sales' => 'render'];
    public $selectedRestaurants;

    public function mount(){
        $this->selectedRestaurants = session('selectedRestaurants');
    }

    public function render()
    {
        $weekMap = [0 => 'DOM', 1 => 'SEG', 2 => 'TER', 3 => 'QUA', 4 => 'QUI', 5 => 'SEX', 6 => 'SAB'];
        
        $restaurant_ids = is_array($this->selectedRestaurants) ? $this->selectedRestaurants : (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->user_id ?? auth()->user()->id)->toArray();

        $lineChartModel = FederatedDaySale::whereIn("restaurant_id", $restaurant_ids)
            ->whereBetween("date", [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()])
            ->groupBy("date")
            ->orderBy("date")
            ->select("federated_day_sales.date")
            ->selectRaw("SUM(federated_day_sales.amount) as amount")    
            ->get()
            ->reduce(function ($lineChartModel, $data) use ($weekMap){
                $date = Carbon::createFromFormat('Y-m-d', $data->date)->dayOfWeek;
                $amount = $data->amount;
                return $lineChartModel->addSeriesPoint("Semana atual", $weekMap[$date], round($amount, 2));
            }, LivewireCharts::lineChartModel()
                ->setTitle('Semana atual X Semana anterior')
                ->multiLine()
                //->setSmoothCurve()
                ->setAnimated(true)
        );

       FederatedDaySale::whereIn("restaurant_id", $restaurant_ids)
            ->whereBetween("date", [Carbon::now()->subDays(13)->toDateString(), Carbon::now()->subDays(7)->toDateString()])
            ->groupBy("date")
            ->orderBy("date")
            ->select("federated_day_sales.date")
            ->selectRaw("SUM(federated_day_sales.amount) as amount")       
            ->get()
            ->reduce(function ($previousWeek, $data) use ($lineChartModel, $weekMap){
                $date = Carbon::createFromFormat('Y-m-d', $data->date)->dayOfWeek;
                $amount = $data->amount;
                return $lineChartModel->addSeriesPoint("Semana anterior", $weekMap[$date], round($amount, 2));
            }
        );

        $orderAverage = FederatedDaySale::whereIn("restaurant_id", $restaurant_ids)
            ->whereBetween("date", [Carbon::now()->subDays(13)->toDateString(), Carbon::now()->toDateString()])
            //->groupBy("date")
            //->orderBy("date")
            ->selectRaw("CEIL(SUM(federated_day_sales.amount) / 14) as amount")            
            ->first();

        foreach($weekMap as $key => $day){
            $lineChartModel->addSeriesPoint("Média", $key, $orderAverage->amount);
        }

        return view('livewire.graph.sales-week')->with(["lineChartModel" => $lineChartModel]);
    }
}
