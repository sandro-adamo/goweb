<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exemplo extends Model
{
    protected $fillable = ['id_pedido', 'campo1', 'campo2', 'campo3', 'campo4'];

    public $timestamps = false;

}
