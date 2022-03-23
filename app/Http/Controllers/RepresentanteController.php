<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepresentanteController extends Controller

{
	public function alteraMovimentacao(Request $request) {
 		 
 		

 	 $id_usuario = \Auth::id();
 		 $nome = \DB::select("select* from usuarios where id = $id_usuario");
 		 
 		 $nome = $nome[0]->nome;
 		 
 		
 		
 		if(isset($request->ah)){$ah = 'AH';}else{$ah = '';}
 		if(isset($request->at)){$at = 'AT';}else{$at = '';}
 		if(isset($request->bg)){$bg = 'BG';}else{$bg = '';}
 		if(isset($request->ev)){$ev = 'EV';}else{$ev = '';}
 		if(isset($request->hi)){$hi = 'HI';}else{$hi = '';}
 		if(isset($request->jm)){$jm = 'JM';}else{$jm = '';}
 		if(isset($request->jo)){$jo = 'JO';}else{$jo = '';}
 		if(isset($request->sp)){$sp = 'SP';}else{$sp = '';}
 		if(isset($request->tc)){$tc = 'TC';}else{$tc = '';}
		 if(isset($request->fe)){$fe = 'FE';}else{$fe = '';}
		 if(isset($request->ai)){$ai = 'AI';}else{$ai = '';}

 		if(isset($request->gu)){$gu = 'GU';}else{$gu = '';}
 		if(isset($request->mm)){$mm = 'MM';}else{$mm = '';}
 		if(isset($request->pu)){$pu = 'PU';}else{$pu = '';}
 		if(isset($request->aa)){$aa = 'AA';}else{$aa = '';}
 		if(isset($request->am)){$am = 'AM';}else{$am = '';}
 		if(isset($request->az)){$az = 'AZ';}else{$az = '';}
 		if(isset($request->br)){$br = 'BR';}else{$br = '';}
 		if(isset($request->bv)){$bv = 'BV';}else{$bv = '';}
 		if(isset($request->cl)){$cl = 'CL';}else{$cl = '';}
 		if(isset($request->ct)){$ct = 'CT';}else{$ct = '';}
 		if(isset($request->mc)){$mc = 'MC';}else{$mc = '';}
 		if(isset($request->sm)){$sm = 'SM';}else{$sm = '';}
 		if(isset($request->st)){$st = 'ST';}else{$st = '';}
 		if(isset($request->bc)){$bc = 'BC';}else{$bc = '';}

 	
 		 		
 		
 		$query = \DB::select("INSERT INTO `movimentacoes_most`( `id_movimentacao`, `tipo`, `codgrife`, `id_destino`, `id_origem`, `status`, `obs`, `responsavel`, `dt_updated`, `dt_created`, `AH`, `AT`, `BG`, `EV`, `HI`, `JM`, `JO`, `PU`, `SP`, `TC`, `AM`, `BV`, `BC`, `CT`, `GU`, `MC`, `MM`, `ST`, `SM`, `AA`, `AZ`, `BR`, `CL` , `FE`, `AI`) VALUES ('$request->id_movimentacao','$request->tipo2','$request->grife2','$request->id_destino2','$request->id_origem2','$request->status','$request->obs','$nome','$request->data_atualizacao','$request->data_inicio2','$ah', '$at', '$bg', '$ev', '$hi', '$jm', '$jo', '$pu', '$sp', '$tc', '$am', '$bv', '$bc', '$ct', '$gu', '$mc', '$mm', '$st', '$sm', '$aa', '$az', '$br', '$cl', '$fe', '$ai')");




 		
 		$request->session()->flash('alert-success', 'Movimentação atualizada');
 		return redirect('/usuarios/movimentacoes/historico/'.$request->id_movimentacao);

 	}

	public function historicoMovimentacao(Request $request) {
 		 
 		 $movimentacoes = \DB::select("SELECT movimentacoes_most.*, ad1.nome as nome_destino, ad2.nome as nome_origem,

(select status from  inventarios where  id_inventario = id_inventario_origem and status <> 'cancelado' order by status desc limit 1) status_inventario_origem,
(select status from  inventarios where  id_inventario = id_inventario_destino and status <> 'cancelado' order by status desc limit 1) status_inventario_destino
			FROM movimentacoes_most
			left join addressbook ad1 on id_destino = ad1.id
			left join addressbook ad2 on id_origem = ad2.id
			
			where id_movimentacao = $request->id
			order by movimentacoes_most.id desc");


 	 return view('sistema.usuarios.movimentacoes.historico')->with('movimentacoes', $movimentacoes);

 	}
	public function listaMovimentacao() {
 		 
 		 $movimentacoes = \DB::select("

		  SELECT id_movimentacao, mm.tipo,  id_destino, id_origem,   ad1.nome as nome_destino, ad2.nome as nome_origem,
						(Select obs from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as obs,
						(Select concat(ifnull(AH,''),' ', ifnull(AT,''),' ', ifnull(BG,''),' ', ifnull(EV,''),' ', ifnull(HI,''),' ', ifnull(JM,''),' ', ifnull(JO,''),' ', ifnull(PU,''),' ',
					  ifnull(SP,''),' ', ifnull(TC,''),' ',
					  ifnull(AM,''),' ', ifnull(BV,''),' ', ifnull(BC,''),' ', ifnull(CT,''),' ', ifnull(GU,''),' ', ifnull(MC,''),' ', ifnull(MM,''),' ', ifnull(ST,'')
					  ,' ', ifnull(SM,''),' ', ifnull(AA,''),' ', ifnull(AZ,''),' ', 
					  ifnull(BR,''),' ',ifnull(CL,''),' ',ifnull(FE,'')) from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as codgrife,
						(Select responsavel from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as responsavel,
						(Select dt_created from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as dt_created,
						(Select dt_updated from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as dt_updated,
						(Select status from movimentacoes_most where id_movimentacao = mm.id_movimentacao order by id desc limit 1) as status ,
				 		 (select concat(id_inventario,'-',status) from  inventarios where tipo = 'enviando' and inventarios.id_movimentacao = mm.id_movimentacao and  inventarios.id_rep = mm.id_origem and   inventarios.status <> 'cancelado'  limit 1) status_inventario_origem   ,
		   (select concat(id_inventario,'-',status) from  inventarios where tipo = 'recebendo' and  inventarios.id_movimentacao = mm.id_movimentacao and  inventarios.id_rep = mm.id_destino  and   inventarios.status <> 'cancelado'  limit 1) status_inventario_destino 
					  
					  FROM movimentacoes_most mm
					  left join addressbook ad1 on id_destino = ad1.id
					  left join addressbook ad2 on id_origem = ad2.id
					  group by id_movimentacao, tipo, codgrife, id_destino, id_origem,id_inventario_destino,id_inventario_origem
					  
	");


 	 return view('sistema.usuarios.movimentacoes.lista')->with('movimentacoes', $movimentacoes);

 	}
 
 	public function inserirMovimentacao(Request $request) {
 	
 		 $id_usuario = \Auth::id();
 		 $nome = \DB::select("select* from usuarios where id = $id_usuario");
 		 $ultimo_id = \DB::select("select case when id_movimentacao is null then 1 else id_movimentacao+1 end as id_prox from movimentacoes_most order by id_movimentacao desc limit 1");
 		 $nome = $nome[0]->nome;
 		 $id_prox = $ultimo_id[0]->id_prox;




 		
 		
 		
 		
 		if(isset($request->ah)){$ah = 'AH';}else{$ah = '';}
 		if(isset($request->at)){$at = 'AT';}else{$at = '';}
 		if(isset($request->bg)){$bg = 'BG';}else{$bg = '';}
 		if(isset($request->ev)){$ev = 'EV';}else{$ev = '';}
 		if(isset($request->hi)){$hi = 'HI';}else{$hi = '';}
 		if(isset($request->jm)){$jm = 'JM';}else{$jm = '';}
 		if(isset($request->jo)){$jo = 'JO';}else{$jo = '';}
 		if(isset($request->sp)){$sp = 'SP';}else{$sp = '';}
 		if(isset($request->tc)){$tc = 'TC';}else{$tc = '';}
		 if(isset($request->ai)){$ai = 'AI';}else{$ai = '';}

 		if(isset($request->gu)){$gu = 'GU';}else{$gu = '';}
 		if(isset($request->mm)){$mm = 'MM';}else{$mm = '';}
 		if(isset($request->pu)){$pu = 'PU';}else{$pu = '';}
 		if(isset($request->aa)){$aa = 'AA';}else{$aa = '';}
 		if(isset($request->am)){$am = 'AM';}else{$am = '';}
 		if(isset($request->az)){$az = 'AZ';}else{$az = '';}
 		if(isset($request->br)){$br = 'BR';}else{$br = '';}
 		if(isset($request->bv)){$bv = 'BV';}else{$bv = '';}
 		if(isset($request->cl)){$cl = 'CL';}else{$cl = '';}
 		if(isset($request->ct)){$ct = 'CT';}else{$ct = '';}
 		if(isset($request->mc)){$mc = 'MC';}else{$mc = '';}
 		if(isset($request->sm)){$sm = 'SM';}else{$sm = '';}
 		if(isset($request->st)){$st = 'ST';}else{$st = '';}
 		if(isset($request->bc)){$bc = 'BC';}else{$bc = '';}

 	
 		 		
 		
 		$query = \DB::select("INSERT INTO `movimentacoes_most`( `id_movimentacao`, `tipo`, `codgrife`, `id_destino`, `id_origem`, `status`, `obs`, `responsavel`, `dt_updated`, `dt_created`, `AH`, `AT`, `BG`, `EV`, `HI`, `JM`, `JO`, `PU`, `SP`, `TC`, `AM`, `BV`, `BC`, `CT`, `GU`, `MC`, `MM`, `ST`, `SM`, `AA`, `AZ`, `BR`, `CL`, `FE` , `AI`) VALUES ('$id_prox','$request->tipo','$request->grife','$request->id_destino','$request->id_origem','$request->status','$request->obs','$nome','$request->data_atualizacao','$request->data_inicio','$ah', '$at', '$bg', '$ev', '$hi', '$jm', '$jo', '$pu', '$sp', '$tc', '$am', '$bv', '$bc', '$ct', '$gu', '$mc', '$mm', '$st', '$sm', '$aa', '$az', '$br', '$cl', '$fe', '$ai')");
 		
 		$request->session()->flash('alert-success', 'Movimentação cadastrada');

 	 return redirect('/usuarios/movimentacoes/lista');
 	 
 	}


 	public function novaMovimentacao() {

 	 return view('sistema.usuarios.movimentacoes.nova');
 	}

	public function atualizaRepresentantes() {



		$representates = \App\AddressBook::where('tipo', 'RE')
									->where('grupo', 'REPRESENTANTES')
									->where('id', '<>', 36742)
									->where('id', '<>', 10142)
									->where('id', '<>', 94532)
									->where('id', '<>', 96395)
									->where('id', '<>', 8165)
									->where('id', '<>', 83184)
									->where('id', '<>', 96970)
									->where('id', '<>', 96395)
									->where('id', '<>', 89887)
									->where('id', '<>', 47989)
									->where('id', '<>', 88673)
									->where('id', '<>', 186916)
									->where('id', '<>', 161323)
									->where('id', '<>', 254599)
									->where('email1', '<>', '.')
			
									->get();

		foreach ($representates as $representate) {


			$usuario = \App\Usuario::where('id_addressbook', $representate->id)->first();

			if ($usuario) {
				echo $usuario->nome;
				// atualiza grifes
				\App\Permissao::where('tabela', 'usuarios')->where('id_tabela', $usuario->id)->where('chave', 'grifes')->delete();

				$grifes = \App\RepGrife::where('an8', $representate->id)->get(['grife']);

				$grifes_liberadas = '';

				if ($representate->sit_representante == 'VO') {

					$usuario->status = 0;
					$usuario->save();
					echo ' status 0 '.$representate->sit_representante.'<br>';

				} else {

					//$usuario->status = 1;
					$usuario->save();
					echo ' status 1-'.$usuario->status.'<br>';
					foreach ($grifes as $grife) {
						$grifes_liberadas .= trim($grife->grife).','; 
					}
					$grifes_liberadas = substr($grifes_liberadas, 0, -1);

					$permissao = new \App\Permissao();
					$permissao->tabela = 'usuarios';
					$permissao->id_tabela = $usuario->id;
					$permissao->chave = 'grifes';
					$permissao->valor = $grifes_liberadas;
					$permissao->save();

				}

			} else {

				if ($representate->sit_representante <> 'VO') {
					$email12 = strtolower($representate->email1);
					
					 $verifica_email = \DB::select( "select* from usuarios where email = '$email12'");
					
					if(count($verifica_email)>0)
					   {
						
						   echo '<FONT color="#ff0000">'.$verifica_email[0]->email.' email já existe para id '.$verifica_email[0]->id_addressbook.'</font></br>';
					   }
					else{

					$novo = new \App\Usuario();
					//$novo->status = 1;
					$novo->id_perfil = 4;
					$novo->id_addressbook = $representate->id;
					$novo->nome = $representate->razao;
					$novo->email = strtolower($representate->email1);
					$novo->password = \Hash::make("mudar123");
					$novo->foto = "logogo.png";
					$novo->admin = 0;
					$novo->lang = 'pt-br';
					$novo->reset = 1;
					$novo->save();


					$grifes = \App\RepGrife::where('an8', $representate->id)->get(['grife']);

					$grifes_liberadas = '';

					foreach ($grifes as $grife) {
						$grifes_liberadas .= trim($grife->grife).','; 
					}
					$grifes_liberadas = substr($grifes_liberadas, 0, -1);

					$permissao = new \App\Permissao();
					$permissao->tabela = 'usuarios';
					$permissao->id_tabela = $novo->id;
					$permissao->chave = 'grifes';
					$permissao->valor = $grifes_liberadas;
					$permissao->save();

				}

					}

			}

		}




		// inativar acesso

		$inativos = \DB::select("select usuarios.id
from usuarios
left join addressbook on id_addressbook = addressbook.id and addressbook.tipo = 'RE' /*and grupo = 'REPRESENTANTES'*/
where id_perfil = 4 and addressbook.id is null and status = 1");


		foreach ($inativos as $inativo) {

			// $usuario = \App\Usuario::find($inativo->id);
			// $usuario->status = 0;
			// $usuario->save();

		}

	}


}
