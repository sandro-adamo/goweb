<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Usuario;
use \SoapClient;

class UsuarioController extends Controller
{
    
	public function listaUsuarios(Request $request) {

		if ($request->pesquisa) {

			$usuarios = Usuario::where('email', 'LIKE', '%'.$request->pesquisa.'%')->orWhere('id_addressbook', $request->pesquisa)->orWhere('nome', 'like', '%'.$request->pesquisa.'%')->orderBy('nome')->paginate(10);
			//$usuarios = Usuario::orderBy('nome')->where('id_addressbook', 'LIKE', '%'.$request->pesquisa.'%')->paginate(10);
			//$usuarios = Usuario::orderBy('nome')->where('nome', 'LIKE', '%'.$request->pesquisa.'%')->paginate(10);

		} else {
			
			$usuarios = Usuario::orderBy('nome')->paginate(10);

		}


		return view('sistema.usuarios.lista')->with('usuarios', $usuarios);

	}

	public function novoUsuario() {

		$usuario = new Usuario();

		$perfis = \App\UsuarioPerfil::where('status',1)->get();

		$menus = \App\Menu::all();
		$grifes   = \App\Item::where('grife', '<>', '')->groupBy('codgrife','grife')->orderBy('grife')->get(["codgrife","grife"]);
		$agrupamentos = \App\Item::where('agrup', '<>', '')->groupBy('agrup')->orderBy('agrup')->get(["agrup"]);
		$colecoes = \App\Item::where('colitem', '<>', '')->groupBy('colitem')->orderBy('colitem','desc')->get(["colitem"]);

		return view('sistema.usuarios.detalhes')->with('usuario', $usuario)
												->with('menus', $menus)
												->with('grifes', $grifes)
												->with('agrupamentos', $agrupamentos)
												->with('colecoes', $colecoes)
												->with('perfis', $perfis);

	}

	public function gravaUsuario(Request $request) {

		$client = \App\JDE::connectAddressBOOK();


		$dados = $request->all();

		if (!empty($request->id_usuario)) {

			$usuario = Usuario::find($request->id_usuario);
			$usuario->id_perfil = $dados["id_perfil"];
			$usuario->id_addressbook = $request->id_addressbook;

			if ($request->id_addressbook <> '') {
				$tipo =  \DB::select("select tipo from addressbook where id = $usuario->id_addressbook");
			} else {
				$tipo = array();
			}
			
			$usuario->status = $request->status;

			if (isset($request->reset) && $request->reset == 1) {
				$usuario->reset = 1;
				$usuario->password = \Hash::make('mudar123');
			}


			 
		
			if ($usuario->id_addressbook <> '' && $usuario->id_perfil == 4  ) {
				
				
				if (isset($request->status) && $request->status == 1 && $tipo[0]->tipo =='RE') {

			       //  $result = $client->processAddressBook( array( 

			       //    "addressBook"=> array(

			       //    	"classifications" => array(
			       //    		"classificationCode004" => ''
			       //    	),
			       //    	"entity" => array(
			       //    		"entityId" => $usuario->id_addressbook
			       //    	),
			       //    	"processing" => array(
			       //    		"actionType" => 2,
			       //    		"processingVersion" => 'GO001'
			       //    	)

			       //    )


			      	// ));

				 	$atualizasit_rep = \DB::select("UPDATE `addressbook` SET sit_representante = '' where id = '$usuario->id_addressbook' ");
				 	$atualizastatus = \DB::select("UPDATE `usuarios` SET status = '1' where id_addressbook = '$usuario->id_addressbook' ");
				}

				if ((isset($request->status) && $request->status == 0 && $tipo[0]->tipo =='RE')){
			
				   

			       //  $result = $client->processAddressBook( array( 

			       //    "addressBook"=> array(

			       //    	"classifications" => array(
			       //    		"classificationCode004" => 'VO'
			       //    	),
			       //    	"entity" => array(
			       //    		"entityId" => $usuario->id_addressbook
			       //    	),
			       //    	"processing" => array(
			       //    		"actionType" => 2,
			       //    		"processingVersion" => 'GO001'
			       //    	)

			       //    )


			      	// ));

			        $atualizasit_rep = \DB::select("UPDATE `addressbook` SET sit_representante = 'VO' where id = '$usuario->id_addressbook' ");
			        $atualizastatus = \DB::select("UPDATE `usuarios` SET status = '0' where id_addressbook = '$usuario->id_addressbook' ");


			 	}
			}			   

			if (isset($request->admin) && $request->admin == 1) {
				$usuario->admin = 1;
			} else {
				$usuario->admin = 0;
			}

			if ($request->email <> $usuario->email) {

				$checa = \App\Usuario::where('email', $request->email)->count();

				if ($checa > 0) {
					$request->session()->flash('alert', 'Já existe um usuário cadastrado com este e-mail');
					return redirect('/usuarios/'.$usuario->id);
				} else {
					$usuario->email = $request->email;
				}

			}



			if ($usuario->api_token == '') {

		        $token = Str::random(60);

		        $usuario->api_token = Str::random(60); //hash('sha256', $token);

		    }			
			$usuario->save();

			$acessos = \App\Acesso::where('tabela', 'usuarios')->where('id_tabela', $usuario->id)->delete();
			if (isset($request->acesso)) {

				foreach ($request->acesso as $acesso) {
					
					$menu = \App\Menu::find($acesso);	
					$acesso = \App\Acesso::setAcesso('usuarios', $usuario->id, $menu->link);

				}


			}			

			if ($usuario) {

				if (isset($request->grifes)) {

					$grifes = implode(",", $request->grifes);

					$permissao = \App\PermissaoUsuario::setPermissao($usuario->id, 'grifes', $grifes);
				}

			}


			if ($usuario) {

				if (isset($request->filtros)) {

					$filtros = implode(",", $request->filtros);

					$permissao = \App\PermissaoUsuario::setPermissao($usuario->id, 'filtros', $filtros);

				}

			}

		} else {
			$usuario = new Usuario();
			$usuario->id_perfil = $dados["id_perfil"];
			$usuario->nome = $dados["nome"];
			$usuario->email = $dados["email"];
			$usuario->id_addressbook = $request->id_addressbook;
	        $usuario->password = \Hash::make(strtolower($dados["senha"]));
	        $usuario->foto = '';
	        $usuario->save();
		}

		$request->session()->flash("alert-success", 'Cadastro atualizado com sucesso');
		return redirect()->back();

	}	

	public function buscaUsuario($id) {

		$usuario = Usuario::find($id);

		$perfis = \App\UsuarioPerfil::where('status',1)->get();

		$grifes   = \App\Item::where('grife', '<>', '')->groupBy('codgrife','grife')->orderBy('grife')->get(["codgrife","grife"]);
		$colecoes = \App\Item::where('colitem', '<>', '')->groupBy('colitem')->orderBy('colitem','desc')->get(["colitem"]);
		$anos = \App\Item::where('anomod', '<>', '')->groupBy('anomod')->orderBy('anomod','desc')->get(["anomod"]);

		return view('sistema.usuarios.detalhes')->with('usuario', $usuario)
												->with('grifes', $grifes)
												->with('colecoes', $colecoes)
												->with('anos', $anos)
												->with('perfis', $perfis);

	}

}
