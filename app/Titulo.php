<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    protected $table = 'titulos';


    public function cliente() {

    	return $this->belongsTo("App\AddressBook", 'id_cliente');

    }
}
