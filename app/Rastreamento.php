<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rastreamento extends Model
{

	protected $table = 'rastreamentos';
	public $timestamps = false;


	public function transportadora() {

		return $this->belongsTo('App\AddressBook', 'id_transportadora');

	}

}
