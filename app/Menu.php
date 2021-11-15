<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    


    public static function listaMenus() {

    	$menus = Self::where('id_menu_pai', NULL)->where('status', 1)->orderBy('ordem')->get();

    	return $menus;

    }


    public static function listaSubMenus($id_menu_pai) {

    	$submenus = Self::where('id_menu_pai', $id_menu_pai)->where('status', 1)->orderBy('ordem')->get();

    	return $submenus;

    }



    public function submenu() {

		return $this->hasMany('App\Menu', 'id_menu_pai');

    }


}
