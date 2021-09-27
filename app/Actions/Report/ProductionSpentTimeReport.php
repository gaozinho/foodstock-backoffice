<?php

namespace App\Actions\Report;

use PdfReport;
use ExcelReport;
use CSVReport;

use App\Models\User;
use App\Models\ProductionSpentTime;
use Illuminate\Support\Facades\DB;

class ProductionSpentTimeReport
{

    public $title = 'Tempo por etapa por usuário';
    public $meta = [];

    public $columns = [
            'Usuário' => 'responsavel',
            'Etapa' => 'nome_etapa',
            'Total de pedidos' => 'total_pedidos',
            'Tempo médio' => 'avg_spent',
        ];

    private function getQueryBuilder($user_ids, $created_at){
        return ProductionSpentTime::select("*")->where("created_at", $created_at)->whereIn("user_id", $user_ids);
    }

    public function displayReport($user_ids, $created_at)
    {
        $this->meta["Data pesquisada"] = date_format(date_create($created_at), "d/m/Y");
        $this->meta["Emitido em"] = date("d/m/Y H:i");
        $queryBuilder = $this->getQueryBuilder($user_ids, $created_at);
        return PdfReport::of($this->title, $this->meta, $queryBuilder, $this->columns)
            ->editColumns(['Total de pedidos', 'Tempo médio'], [
                'class' => 'right'
            ])
            ->stream();
    }
}