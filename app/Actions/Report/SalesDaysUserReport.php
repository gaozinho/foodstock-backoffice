<?php

namespace App\Actions\Report;

use PdfReport;
use ExcelReport;
use CSVReport;

use App\Models\User;
use App\Models\SalesDaysUser;
use Illuminate\Support\Facades\DB;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class SalesDaysUserReport
{

    public $title = 'Vendas apuradas no dia (apenas produtos)';
    public $meta = [];
    public $reportTitle = "FOODSTOCK - Vendas apuradas - %s";

    public $columns = [
            'Produto' => 'name',
            'Quantidade' => 'quantity',
            'Total (R$)' => 'total',
        ];

    private function getQueryBuilder($user_id, $created_at){
        return SalesDaysUser::select("*")->where("date", $created_at)->where("user_id", $user_id);
    }

    public function displayPdfReport($user_id, $created_at)
    {
        $this->meta["Data apurada"] = date_format(date_create($created_at), "d/m/Y");
        $this->meta["Emitido em"] = date("d/m/Y H:i");
        $this->meta["Emitido por"] = 'FOODSTOCK.COM.BR';
        $queryBuilder = $this->getQueryBuilder($user_id, $created_at);


        return response()->streamDownload(function () use ($queryBuilder) {
            echo PdfReport::of($this->title, $this->meta, $queryBuilder, $this->columns)
                ->editColumns(['Quantidade', 'Total (R$)'], [
                    'class' => 'right'
                ])
                ->stream();
        }, sprintf($this->reportTitle, date("d-m-Y H\hi")) . ".pdf", ['Content-Type' => 'application/pdf']);

    }

    public function displayExcelReport($user_id, $created_at)
    {
        $this->meta["Data apurada"] = date_format(date_create($created_at), "d/m/Y");
        $this->meta["Emitido em"] = date("d/m/Y H:i");
        $this->meta["Emitido por"] = 'FOODSTOCK.COM.BR';
        $queryBuilder = $this->getQueryBuilder($user_id, $created_at);
        return ExcelReport::of($this->title, $this->meta, $queryBuilder, $this->columns)
            ->editColumns(['Quantidade', 'Total'], [
                'class' => 'right'
            ])
            ->setOrientation('portrait')
            ->download(sprintf($this->reportTitle, date("d-m-Y H\hi")));
    }    
}