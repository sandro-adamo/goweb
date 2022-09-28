<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{

    protected $table = 'fornecedores_portfolios';

    protected $guarded = [];

    public function itens(){

        return $this->hasMany(PortfolioItem::class, 'id_portfolio', 'id');

    }

}
