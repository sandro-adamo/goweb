<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
 

	public static function geraSupervisor() {


		$supervisores = \DB::select("select * from (

								select id_supervisor
								from addressbook
								where id_supervisor <> 0
								group by id_supervisor

								) as base

								left join addressbook on base.id_supervisor = id");


		foreach ($supervisores as $supervisor) {


			$usuario = \App\Usuario::where('id_addressbook', $supervisor->id_supervisor)->first();


			if (!$usuario) {

				$novo = new \App\Usuario();
				$novo->status = 1;
				$novo->id_perfil = 6;
				$novo->id_addressbook = $supervisor->id_supervisor;
				$novo->nome = $supervisor->razao;
				$novo->email = $supervisor->email;
				$novo->password = \Hash::make("mudar123");
				$novo->reset = 0;
				$novo->foto = 'logogo.png';
				$novo->admin = 0;
				$novo->lang = 'pt-br';

				$novo->save();



			}



		}



	}

}
