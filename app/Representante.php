<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
    

	public function atualizaBaseRepresentante() {


		$addressbook = \App\AddressBook::where('tipo', 'RE')->get();


		foreach ($addressbook as $rep) {


			$usuario = \App\Usuario::where('id_addressbook', $rep->id)->first();

			if ($usuario) {



			} else {

				$novo = new \App\Usuario();
				$novo->status = 1;
				$novo->id_perfil = 4;
				$novo->id_addressbook = $rep->id;
				$novo->nome = $rep->razao;
				$novo->email = $rep->email;
				$novo->password = \Hash::make("mudar123");
				$novo->reset = 1;
				$novo->foto = 'logogo.png';
				$novo->admin = 0;
				$novo->lang = 'pt-br';

				$novo->save();

			}



		}



	}


}
