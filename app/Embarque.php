<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embarque extends Model
{
    protected $table = 'fornecedores_embarques';

    protected $guarded = [];

    public function item(){

        return $this->belongsTo(Item::class, 'secundario', 'id');

    }

    public function portfolio(){
        
        return $this->belongsTo(Portfolio::class, 'id_portfolio', 'id');

    }

    public function portfolioItem(){
        
        return $this->belongsTo(PortfolioItem::class, 'id_portfolio_item', 'id');

    }

}
