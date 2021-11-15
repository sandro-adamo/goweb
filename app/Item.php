<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'itens';


    public function agrupamentos() {

    	return $this->belongsTo('App\Item', 'codgrife', 'codgrife')->groupBy('codgrife');

    }

    public function colecoes() {

    	return $this->hasMany('App\Item', 'codagrup', 'codagrup')->where('anomod', '>=', '2015')->where('anomod', '<>', 'CANCELADO')->select('colmod');

    }
	
	


    public function colecoesAno($ano) {

        return $this->where('anomod', $ano)->groupBy('colmod')->select('colmod')->orderBy('colmod')->get();

    }


    public function anos() {



        if ( \Auth::user()->admin == 1 ) {
            $colecoes =  $this->groupBy('colmod')->where('anomod', '<>', 'CANCELADO')->select('colmod')->orderBy('colmod')->get();
        } else {
            $colecoes = \App\Permissao::getPermissao( \Auth::id(), 'colecoes');            
        }

        if ($colecoes && count($colecoes) > 0) {

            return $this->hasMany('App\Item', 'codagrup', 'codagrup')->where('anomod', '>=', '2015')
                 ->where('anomod', '<>', 'CANCELADO')                                                     
                 ->whereIn('colmod', $colecoes)
                 ->groupBy('anomod')
                 ->orderBy('anomod', 'desc')
                 ->select('anomod');

        } else {

            return $this->hasMany('App\Item', 'codagrup', 'codagrup')
                ->where('anomod', '<>', 'CANCELADO')                                                     
                ->groupBy('anomod')
                ->orderBy('anomod', 'desc')
                ->select('anomod');

        }


    }


}
