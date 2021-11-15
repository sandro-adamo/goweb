<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    
	protected  $table = 'carteira';


	public function cliente() {

		return $this->belongsTo('App\Cliente', 'cli');

	}


}
