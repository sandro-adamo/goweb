<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    
	protected  $table = 'atendimentos';
	protected  $connection = 'goweb';


	public function responsavel() {

		return $this->belongsTo("App\AtendimentoAtendente", 'id_usuario');

	}

}
