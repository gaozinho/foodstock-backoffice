<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'eventos';

    protected $fillable = ['nome','descricao','data_inicio','data_fim','link','imagem','is_active'];
	
}
