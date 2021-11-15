@extends('layout.principal')
@php

  function getMes($mes) {
    $mes2 = '';

    switch($mes) {
        case 1:
          $mes2 = 'Janeiro';
          break;
        case '2':
          $mes2 = 'Fevereiro';
          break;
        case '3':
          $mes2 = 'Março';
          break;
        case '4':
          $mes2 = 'Abril';
          break;
        case '5':
          $mes2 = 'Maio';
          break;
        case '6':
          $mes2 = 'Junho';
          break;
        case '7':
          $mes2 = 'Julho';
          break;
        case '8':
          $mes2 = 'Agosto';
          break;
        case '9':
          $mes2 = 'Setembro';
          break;
        case '10':
          $mes2 = 'Outubro';
          break;
        case '11':
          $mes2 = 'Novembro';
          break;
        case '12':
          $mes2 = 'Dezembro';
          break;

        default:
          $mes2 = 'nao definido';
    }

    return $mes2;

  }

  if (isset($_GET["periodo"])) {

    $periodo = explode('-', $_GET["periodo"]);

    $ano2 = $periodo[1];
    $mes2 = $periodo[0];   


  } else {

    $ano2 = date('Y');
    $mes2 = date('m');

  }

@endphp


@section('title')
<form action="" method="get" class="form-horizontal">
<div class="row ">
  <div class="col-md-2">
    <button type="button" data-toggle="modal" data-target="#modalFiltros" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> Filtros</button>
  </div>
  <div class="col-md-6">
    <i class="fa fa-dashboard"></i> Dashboard
  </div>
</div>
</form>
@append 

@section('conteudo')

@php
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 header("Cache-Control: no-cache");
 header("Pragma: no-cache");
	$id_representante = \Auth::user()->id_addressbook;

  if ($id_representante == '') {
    $id_representante = 0;
  }

  $representantes = Session::get('representantes');


  $vendas = array();

	$vendas = \DB::select("select sum(valor) as venda
							from vendas_jde 
							where id_rep in ($representantes)
								and ano = $ano2
								and mes = $mes2
								and ult_status not in ('980','984')");

  $vendas_grife = \DB::select("select grife, sum(qtde) as pecas, sum(valor) as valor
              from vendas_jde 
              left join itens on id_item = itens.id
              where id_rep in ($representantes)
                and ano = $ano2
                and mes = $mes2
                and ult_status not in ('980','984')
                group by grife");


  $cancelados = array();

  $cancelados = \DB::select("select sum(valor) as cancelados
              from vendas_jde 
              where id_rep in ($representantes)
                and ano = $ano2
                and mes = $mes2
                and ult_status in ('980','984')");

$bloqueados = array();

  $bloqueados = \DB::select("select sum(valor) as bloqueados
              from vendas_jde vendas
			  left join suspensoes on vendas.pedido = suspensoes.pedido and suspensoes.tipo = vendas.tipo

              where ult_status not in ('980','984') and suspensoes.codigo is not null and id_rep in ($representantes)
                and ano = $ano2
                and mes = $mes2
                ");

$devolvidos = array();
  $devolvidos = \DB::select("select sum(dev.valor)*-1 devolvidos
              from devolucoes dev
				left join pedidos_jde ped on ped.pedido = dev.ped_original and ped.linha = dev.linha_original and dev.tipo_original = ped.tipo
                
				where dev.tipo_original = 'so'
				and ped.id_rep in ($representantes)
                and year(dev.dt_emissao) = $ano2
                and month(dev.dt_emissao) = $mes2
                ");



$faturamentos = array();

  $faturamentos = \DB::select("select sum(total) as faturamento
              from notas_jde 
              left join itens on id_item = itens.id

              where 
                id_rep in ($representantes)
                and codtipoitem = '006'
                and ano = $ano2
                and mes = $mes2
                -- and ult_status = '620' 
                and prox_status in (610,617,620,999)
                -- and prox_status = '999'");
  $orcamentos = array();

$carteira = array();

  $carteira = \DB::select("select ifnull(sum(Inativo),0) as Inativo, ifnull(sum(Inadimplente),0) as Inadimplente, ifnull(sum(Juridico),0) as Juridico, ifnull(sum(Ativo),0) as Ativo,
                      (ifnull(sum(Inativo),0) + ifnull(sum(Inadimplente),0) + ifnull(sum(Juridico),0) + ifnull(sum(Ativo),0) ) as total

              from(
                select 
                  case when financeiro = 'CI' then count(*) end as 'Inativo',
                  case when financeiro = 'IN' then count(*) end as 'Inadimplente',
                  case when financeiro = 'JU' then count(*) end as 'Juridico',
                  case when financeiro not in ('CI', 'IN', 'JU') then count(*) end as 'Ativo'
                from (
                  select cli
                  from carteira
                  where rep in ($representantes)
                  group by cli
                ) as base
                left join addressbook on cli = addressbook.id
                group by financeiro
              ) as fim");

  $mostruarios = array();


  $dia1mes = date('Y').'-'.date('m').'-01';

  $financeiro = array();

@endphp

      <div class="row">
        <div class="col-md-12">
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-suitcase"></i> Em desenvolvimento</h3>
              <p>Dados apenas para teste, por favor desconsiderar</p>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <!-- /.users-list -->
               <div class="chart">
                <canvas id="myChart3" style="height:230px"></canvas>
              </div>
            <!-- /.box-body -->
            </div>
            <div class="box-footer">
                <div id="tabela">
                    <table class="table table-bordered text-center table-responsive">
                    <thead class="table-striped">
                        <tr>
                        <th>Grifes</th>
                        <th>Fidelizados</th>
                        <th>Não Fidelizados</th>
                        <th>A Recuperar</th>
                        <th>Recuperados</th>
                        <th>Sem Vendas</th>
                        <th>Novos</th>
                        <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $fid = 'fidelizado';
                            $n_fid = 'naofidelizado';
                            $arec =  'arecuperar';
                            $rec = 'recuperado';
                            $sem_v = 'svendas';
                            $nov = 'novos';
                            $arraySts = [$fid, $n_fid, $arec, $rec, $sem_v, $nov];
                            $grf = '';
                            $sts = '';

                            foreach($carteiraSts as $cats) {
                                if($cats->sts_carteira == 'a - fidelizado') $fideliza = $cats->qtd;
                                if($cats->sts_carteira == 'd - nao_fidelizado') $n_fideliza = $cats->qtd;
                                if($cats->sts_carteira == 'e - a_recuperar') $a_recup = $cats->qtd;
                                if($cats->sts_carteira == 'b - recuperado') $recupera = $cats->qtd;
                                if($cats->sts_carteira == 'f - sem_venda') $sem_venda = $cats->qtd;
                                if($cats->sts_carteira == 'c - novo') $novos = $cats->qtd;     
                            }
                        @endphp

                        @foreach ($carteiraGrife as $catgri)
                            <tr>
                                <th scope='row'>{{$catgri->grife}}</th>
                                <td><a href="testes/{{$sts = $arraySts[0]}}/{{$catgri->grife}}">{{$grf = $catgri->fidelizados}}</a></td>
                                <td><a href="testes/{{$sts = $arraySts[1]}}/{{$catgri->grife}}">{{$grf = $catgri->n_fidelizados}}</a></td>
                                <td><a href="testes/{{$sts = $arraySts[2]}}/{{$catgri->grife}}">{{$grf = $catgri->a_recuperar}}</a></td>
                                <td><a href="testes/{{$sts = $arraySts[3]}}/{{$catgri->grife}}">{{$grf = $catgri->recuperados}}</a></td>
                                <td><a href="testes/{{$sts = $arraySts[4]}}/{{$catgri->grife}}">{{$grf = $catgri->sem_vendas}}</a></td>
                                <td><a href="testes/{{$sts = $arraySts[5]}}/{{$catgri->grife}}">{{$grf = $catgri->novos}}</a></td>
                                <td><a href="testes/">{{$catgri->total}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            <!-- /.box-footer -->
          </div>
          <!--/.box -->
       </div>
       <input class="btn btn-success" type="button" value="Criar PDF" id="btnImprimir" onclick="getPDF()" />
    </div>

    <form action="" method="get" class="form-horizontal">
      <div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-filter"></i> Filtros</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="col-md-1 control-label">Grifes</label>
                @foreach($carteiraGrife as $catgri)
                <div class="form-check col-md-1">
                <input type="checkbox" name="{{$catgri->grife}}" class="form-check-input" id="{{$catgri->grife}}" @if (isset($_GET[$catgri->grife]) && $_GET[$catgri->grife] == $catgri->grife) selected @endif>
                  <label class="form-check-label" for="{{$catgri->grife}}">{{$catgri->grife}}</label>
                </div>
                @endforeach
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Status</label>
                @foreach($carteiraSts as $sts)
                <div class="form-check col-md-3">
                <input type="checkbox" name="{{$sts->sts_carteira}}" class="form-check-input" id="{{$sts->sts_carteira}}" @if (isset($_GET[$sts->sts_carteira]) && $_GET[$sts->sts_carteira] == $sts->sts_carteira) selected @endif>
                  <label class="form-check-label" for="{{$sts->sts_carteira}}">{{$sts->sts_carteira}}</label>
                </div>
                @endforeach
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary btn-flat">Filtrar</button>
            </div>
          </div>
        </div>
      </div>
      </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>
    <script src="https://superal.github.io/canvas2image/canvas2image.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        function getPDF()  {
       html2canvas(document.getElementById("tabela"),{
        onrendered:function(canvas){
            
        var dia = new Date();
        var convert = dia.toLocaleDateString();
        var img = canvas.toDataURL("image/png");
        var doc = new jsPDF('l', 'cm'); 
        doc.addImage(img,'PNG',0.5,0.5);
        doc.save('reporte_' + convert + '.pdf');
       }
    }); 
}
    </script>
    

@stop