<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{

	protected $table = 'compras';
	public $timestamps = false;



	public function fornecedor() {


		return $this->belongsTo('App\AddressBook', 'id_fornecedor');

	}

}
