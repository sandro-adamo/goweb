<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsuarioPerfil;

class UsuarioPerfilController extends Controller
{
    

	public function listaPerfis() {

		$perfis = UsuarioPerfil::orderBy('descricao')->get();

		return view('sistema.perfis.lista')->with('perfis', $perfis);
	}

	public function novoPerfil() {

		$perfil = new UsuarioPerfil();

		$menus = \App\Menu::all();


		$agrupamentos = \App\Item::where('agrup', '<>', '')->groupBy('agrup', 'codagrup')->orderBy('agrup', 'codagrup')->get(["agrup", "codagrup"]);

		$grifes   = \App\Item::where('grife', '<>', '')->groupBy('codgrife','grife')->orderBy('grife')->get(["codgrife","grife"]);
		$colecoes = \App\Item::where('colitem', '<>', '')->groupBy('colitem')->orderBy('colitem','desc')->get(["colitem"]);
		$anos = \App\Item::where('anomod', '<>', '')->groupBy('anomod')->orderBy('anomod','desc')->get(["anomod"]);
		//return view('sistema.perfis.detalhes')->with('perfil', $perfil)->with('menus', $menus);


		return view('sistema.perfis.detalhes')->with('perfil', $perfil)
											  ->with('menus', $menus)
											  ->with('grifes', $grifes)
											  ->with('colecoes', $colecoes)
											  ->with('anos', $anos)
											  ->with('agrupamentos', $agrupamentos);
		 									
		
	}

	public function buscaPerfil($id) {

		$perfil = UsuarioPerfil::find($id);

		$menus = \App\Menu::all();

		$agrupamentos = \App\Item::where('agrup', '<>', '')->groupBy('agrup', 'codagrup')->orderBy('agrup', 'codagrup')->get(["agrup", "codagrup"]);

		$grifes   = \App\Item::where('grife', '<>', '')->groupBy('codgrife','grife')->orderBy('grife')->get(["codgrife","grife"]);
		$colecoes = \App\Item::where('colitem', '<>', '')->groupBy('colitem')->orderBy('colitem','desc')->get(["colitem"]);
		$anos = \App\Item::where('anomod', '<>', '')->groupBy('anomod')->orderBy('anomod','desc')->get(["anomod"]);


		return view('sistema.perfis.detalhes')->with('perfil', $perfil)
											  ->with('menus', $menus)
											  ->with('grifes', $grifes)
											  ->with('colecoes', $colecoes)
											  ->with('anos', $anos) 
											  ->with('anos2', 'teste') 
											  ->with('agrupamentos', $agrupamentos);
		
	}


	public function gravaPerfil(Request $request) {


		if (isset($request->id_perfil)) {
		
			$id_perfil = $request->id_perfil;
	
			$perfil = \App\UsuarioPerfil::find($id_perfil);
			$perfil->status = $request->status;
			$perfil->descricao = $request->descricao;
			$perfil->home = $request->home;
			$perfil->save();
			
			// limpa os acessos e as permissoes para o perfil
			$acessos = \App\Acesso::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->delete();
			$permissoes = \App\Permissao::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->delete();
		

		} else {

			$novo = new \App\UsuarioPerfil();
			$novo->status = 1;
			$novo->descricao = $request->descricao;
			$novo->home = $request->home;
			$novo->save();

			$id_perfil = $novo->id;

		}


		if (isset($request->acessos)) {
			foreach ($request->acessos as $rota) {

				$permissao = new \App\Acesso();
				$permissao->tabela = 'perfis';
				$permissao->id_tabela = $id_perfil;
				$permissao->rota = $rota;
				$permissao->save();

			} 
		}


		if (isset($request->filtros)) {

			$filtros = implode(",", $request->filtros);

			$permissao = \App\PermissaoPerfil::setPermissao($id_perfil, 'filtros', $filtros);

		}



		if ($request->grifes) {
			$grifes = implode(",", $request->grifes);


			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'grifes';
			$permissao->valor = $grifes;
			$permissao->save();		
		}

		if ($request->colecoes) {
			$colecoes = implode(",", $request->colecoes);

			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'colecoes';
			$permissao->valor = $colecoes;
			$permissao->save();		
		}


		if (isset($request->vendas)) {

			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'vendas';
			$permissao->valor = 1;
			$permissao->save();	

		} 



		if (isset($request->estoques)) {

			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'estoques';
			$permissao->valor = 1;
			$permissao->save();	
				
		} 



		if (isset($request->preco_venda)) {

			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'valor';
			$permissao->valor = 1;
			$permissao->save();	
				
		} 



		if (isset($request->preco_custo)) {

			$permissao = new \App\Permissao();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = 'custo';
			$permissao->valor = 1;
			$permissao->save();	
				
		} 

		\Session::flash('alert', 'Alterações realizadas com sucesso');

		return redirect('/perfis/'.$id_perfil);

		//print_r($request->all());


	}

}
