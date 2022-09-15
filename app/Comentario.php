<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'fornecedores_portfolios_comentarios';

    protected $guarded = [];

    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id_usuario', 'id');
    }

    public function addressbook(){
        return $this->belongsTo('App\Addressbook', 'id_addressbook', 'id');
    }

}
