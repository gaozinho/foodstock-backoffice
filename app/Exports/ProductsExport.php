<?php

namespace App\Exports;

use App\Performance;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithProperties;

class ProductsExport implements FromQuery, WithHeadings, WithProperties
{

    use Exportable;

    public function filtro($filter_eventos)
    {
        $this->filter_eventos = $filter_eventos;
        return $this;
    }    

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {

        $query = \App\Models\Evento::query();
        $filter =$this->filter_eventos;

        if(strlen(trim($filter)) > 0){
            $query->orWhere('nome', 'like', '%' . $filter . '%')
                ->orWhere('descricao', 'like', '%' . $filter . '%');
        }         

        /*
        if(intval($this->empresa_id) > 0) $query->where("empresa_id", "=", $this->empresa_id);
        if(intval($this->plano_id) > 0) $query->where("plano_id", "=", $this->plano_id);
        if(intval($this->curso_id) > 0) $query->where("curso_id", "=", $this->curso_id);

        $user = auth()->user();

        if($user->hasRole('Admin')){

        }elseif($user->hasRole('Gestor')){
            $query->where("empresa_id", "=", $user->empresa_id);
        }
        
        if(intval($this->chave) != ""){
            $query->where(function ($query) use ($filter) {
                $query->orWhere('CPF', '=', $filter)
                    ->orWhere('E-mail', 'like', '%' . $filter . '%');
            });
        } 
        */

        return $query->orderBy("nome")->select('id',
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'link',
        'imagem',
        'is_active');
    }

    public function headings() : array
    {
        return [
            'id',
            'nome',
            'descricao',
            'data_inicio',
            'data_fim',
            'link',
            'imagem',
            'is_active'
        ];
    } 

    public function properties(): array
    {
        return [
            'creator'        => 'Atma Interativa',
            'lastModifiedBy' => 'Atma Interativa',
            'title'          => 'Relatório de Products',
            'description'    => '',
            'subject'        => '',
            'keywords'       => '',
            'category'       => '',
            'manager'        => '',
            'company'        => 'Powered by ATMA INTERATIVA',
        ];
    }    

}
