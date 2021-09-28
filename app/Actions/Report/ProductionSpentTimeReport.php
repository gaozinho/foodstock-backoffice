<?php

namespace App\Actions\Report;

use PdfReport;
use ExcelReport;
use CSVReport;

use App\Models\User;
use App\Models\ProductionSpentTime;
use Illuminate\Support\Facades\DB;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class ProductionSpentTimeReport
{

    public $title = 'Produtividade da equipe';
    public $meta = [];
    public $reportTitle = "FOODSTOCK - Produtividade da equipe - %s";

    public $columns = [
            'Responsável' => 'responsavel',
            'Etapa' => 'nome_etapa',
            'Pedidos atendidos' => 'total_pedidos',
            'Tempo médio' => 'avg_spent',
        ];

    private function getQueryBuilder($user_ids, $created_at){
        $restaurant_ids = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
        return ProductionSpentTime::select("*")->where("created_at", $created_at)->whereIn("restaurant_id", $restaurant_ids);
    }

    public function displayPdfReport($user_ids, $created_at)
    {
        $this->meta["Data apurada"] = date_format(date_create($created_at), "d/m/Y");
        $this->meta["Emitido em"] = date("d/m/Y H:i");
        $queryBuilder = $this->getQueryBuilder($user_ids, $created_at);


        return response()->streamDownload(function () use ($queryBuilder) {
            echo PdfReport::of($this->title, $this->meta, $queryBuilder, $this->columns)
                ->editColumns(['Pedidos atendidos', 'Tempo médio'], [
                    'class' => 'right'
                ])
                ->stream();
        }, sprintf($this->reportTitle, date("d-m-Y H\hi")) . ".pdf", ['Content-Type' => 'application/pdf']);

    }

    public function displayExcelReport($user_ids, $created_at)
    {
        $this->meta["Data apurada"] = date_format(date_create($created_at), "d/m/Y");
        $this->meta["Emitido em"] = date("d/m/Y H:i");
        $queryBuilder = $this->getQueryBuilder($user_ids, $created_at);
        return ExcelReport::of($this->title, $this->meta, $queryBuilder, $this->columns)
            ->editColumns(['Pedidos atendidos', 'Tempo médio'], [
                'class' => 'right'
            ])
            ->setOrientation('portrait')
            ->download(sprintf($this->reportTitle, date("d-m-Y H\hi")));
    }    
}