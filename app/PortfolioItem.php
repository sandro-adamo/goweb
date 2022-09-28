<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $table = 'fornecedores_portfolios_itens';

    protected $guarded = [];

    public function item(){

        return $this->belongsTo(Item::class, 'secundario', 'id');

    }

    public function estrutura(){

        return $this->belongsTo(ItemEstrutura::class, 'secundario', 'item_pai');

    }

    public function portfolio(){
        
        return $this->belongsTo(Portfolio::class, 'id_portfolio', 'id');

    }

    public function comentarios(){
        return $this->hasMany(Comentario::class, 'id_fornecedor_portfolio_item', 'id');
    }

    public function invoice(){
        return $this->hasOne(CompraInvoice::class, 'id', 'id_compra_invoice');
    }

}
