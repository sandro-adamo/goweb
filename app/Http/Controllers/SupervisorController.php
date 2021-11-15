<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{




	public static function atualizaSupervisor() {


		$supervisores = \DB::select("select * from (

								select id_supervisor
								from addressbook
								where id_supervisor <> 0
								group by id_supervisor

								) as base

								left join addressbook on base.id_supervisor = id");


		foreach ($supervisores as $supervisor) {

			$usuario = \App\Usuario::where('id_addressbook', $supervisor->id)->first();

			if (!$usuario) {

				$novo = new \App\Usuario();
				$novo->status = 1;
				$novo->id_perfil = 6;
				$novo->id_addressbook = $supervisor->id;
				$novo->nome = $supervisor->razao;
				$novo->email = $supervisor->email1;
				$novo->password = \Hash::make("mudar123");
				$novo->reset = 0;
				$novo->foto = 'logogo.png';
				$novo->admin = 0;
				$novo->lang = 'pt-br';

				$novo->save();

			} else {

				$usuario->id_perfil = 6;
				$usuario->save();

			}



		}



	}

}
