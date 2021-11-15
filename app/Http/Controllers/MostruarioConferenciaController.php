<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \hpOffice\PhpSpreadsheet\Worksheet;

class MostruarioConferenciaController extends Controller
{

   public function confirmaConferencia($id) {


      $id_usuario = \Auth::id();

      $query = \DB::select("update conferencias set status_lista = 'Enviada' where id_usuario = '$id_usuario' and id_lista = '$id' ");


      return redirect('/mostruarios/conferencias');

   }


   public function geraListaExcel($id) {

      $id_usuario = \Auth::id();

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');


      $query = \DB::select("select agrup, conferencias.secundario, count(*) as qtde
                              from conferencias
                              left join itens on id_item = itens.id
                              where id_usuario = $id_usuario and id_lista = $id and status = 1 
                              group by agrup, conferencias.secundario");

      foreach ($query as $registro) {

         $linha++;

         if ($linha > 1) {
            $sheet->setCellValue('A'.$linha, $registro->agrup);
            $sheet->setCellValue('B'.$linha, $registro->secundario);
            $sheet->setCellValue('C'.$linha, $registro->qtde);
         }

      }

      $writer = new Xlsx($spreadsheet);
   // $writer->save('hello world.xlsx');     
      
      $nome = 'dev_'.$id.'.xlsx';
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$nome.'"');
      
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');


   }

   public function finalizaConferencia($id) {

      $id_usuario = \Auth::id();


      $query = \DB::select("update conferencias set status_lista = 'Aguardando Conferência' where id_usuario = '$id_usuario' and id_lista = '$id' ");


      return redirect('/mostruarios/conferencias');


   }

   public function excluirItem($id, $id_item) {

      $id_usuario = \Auth::id();


      $query = \DB::select("update conferencias set status = 0 where id_usuario = '$id_usuario' and id_lista = '$id' and id = '$id_item'");


      return redirect()->back();


   }
 
   public function novaConferencia() {

      $id_usuario = \Auth::id();

      $checa_lista_aberto = \DB::select("select * from conferencias where id_usuario = $id_usuario and status_lista = 'Em Aberto'");

      if (!$checa_lista_aberto) {

         $query = \DB::select("select ifnull(max(id_lista),0) as ultimo_id from conferencias where id_usuario = $id_usuario");

         if ($query) {

            $id_lista = $query[0]->ultimo_id + 1;

         }

      } else {

         $id_lista = $checa_lista_aberto[0]->id_lista;

      }

      return redirect('/mostruarios/conferencias/'.$id_lista);

   }

   public function listaconferencias() {

      $id_usuario = \Auth::id();


      $listas = \DB::select("select * from conferencias where id_usuario = $id_usuario order by id_lista desc");

      return view('mostruarios.conferencias.lista')->with('listas', $listas);

   }

   public function detalhesConferencia($id) {

      $id_usuario = \Auth::id();

      $grifes = Session::get('grifes');

      $itens = \DB::select("select conferencias.*, statusatual, codgrife, grife, modelo, conferencias.secundario, codgrife, 
              case when statusatual in ('ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
              then ' MANTER' else 'DEVOLVER' end as Situacao_Peca

            from conferencias
            left join itens on conferencias.id_item = itens.id 

            where id_usuario = $id_usuario and id_lista = '$id' and conferencias.status = 1
            order by id desc ");

      return view('mostruarios.conferencias.detalhes')->with('id_lista', $id)->with('itens', $itens);

   }

   public function confereDevolucaoItem(Request $request) {

dd('teste');

      if ($request->referencia) {


         $id_usuario = \Auth::id();

         $item = \App\Item::where('secundario', $request->referencia)->first();
        
         if ($item) {

            $grifes = Session::get('grifes');

            $situacao = \DB::select("

               select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
              case 
              
              when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0))-orcvalido >=1
              AND statusatual in ('EM PRODUÇÃO','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS')
              then 'MANTER'
              when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0))-orcvalido <1
              then 'DEVOLVER'
              WHEN fornecedor like '%kering%'
              AND colmod >= 2020 
              then 'MANTER'
              
              when statusatual in ('EM PRODUÇÃO','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS')
              and fornecedor not like '%kering%'
              then 'MANTER'
              when statusatual = 'ESGOTADO' 
              and colmod > 2019
              and fornecedor not like '%kering%'
              then 'MANTER'
              when statusatual = 'ESGOTADO' 
              and colmod > 2018
              and clasmod in ('linha a+', 'linha a++')
              and fornecedor not like '%kering%'
              then 'MANTER'
              
              when statusatual = 'ESGOTADO' 
              and colmod < 2020
              then 'DEVOLVER'
              
              
              else 'DEVOLVER' end as Situacao_Peca
              
              from itens
              left join saldos on saldos.secundario = itens.secundario
              left join producoes_sint on itens.secundario = producoes_sint.cod_sec
              left join orcamentos on itens.secundario = orcamentos.secundario

             where itens.secundario = '$item->secundario'");

           

            if ($situacao) {

               $id_lista = $request->id_lista;

               $situacao_item = $situacao[0]->Situacao_Peca;



               $conferirduplicada = \DB::select("select secundario from conferencia id_usuario = '$id_usuario' and id_lista= '$id_lista'and secundario = '$item->secundario' ");
           

              

               $query = \DB::select("insert into conferencias ( id_usuario, id_lista, id_item, secundario, situacao) values ($id_usuario, '$id_lista', $item->id, '$item->secundario', '$situacao_item') ");

          

         } else {

            $request->session()->flash('alert-warning', 'Item não encontrado');

         }}


      }


      return redirect('/mostruarios/conferencias/'.$id_lista)->with('item', $situacao);


   }

	public function listaPedidos(Request $request) {

		$sql = '';


		if ($request->filial) {

			$sql .= " and filial = '$request->filial' ";

		}

		if ($request->razao) {

			$sql .= " and descricao_AN8 like '$request->razao%' ";
		}

		if ($request->codigo) {

			$sql .= " and AN8 = '$request->codigo' ";
		}

		if ($request->ped_inicio && $request->ped_fim) {

			$sql .= " and (dt_pedido >= '$request->ped_inicio' and dt_pedido <= '$request->ped_fim')";
		}

		if ($request->num_pedido) {

			$sql .= " and pedido = '$request->num_pedido' ";
		}


		if ($request->ns_inicio && $request->ns_fim) {

			$sql .= " and (dt_nf >= '$request->ns_inicio' and dt_nf <= '$request->ns_fim')";
		}

		if ($request->num_ns) {

			$sql .= " and ns = '$request->num_ns' ";
		}


		if ($request->nf_inicio && $request->nf_fim) {

			$sql .= " and (dt_nf >= '$request->nf_inicio' and dt_nf <= '$request->nf_fim')";
		}

		if ($request->num_nf) {

			$sql .= " and nf = '$request->num_nf' ";
		}


		$pedidos = \DB::select("select descricao_AN8 as razao, filial, pedido, tipo, dt_pedido, ns, nf, dt_nf, sum(mala) as pecas,
(select case 
			when max(ult_status) <= '540' then 'Pedido Emitido' 
			when max(ult_status) > '540' and max(ult_status) < '620'then 'Em separação' 
			when max(ult_status) = '620' then 'Faturado' 
		end 
	from mostruarios m2 
	where m2.pedido = pedido and ult_status < '900') as status
from mostruarios
where id is not null $sql
group by descricao_AN8, filial, pedido, tipo, dt_pedido, ns, nf, dt_nf
limit 10");

		return view('mostruarios.lista')->with('pedidos', $pedidos);

	}
}
