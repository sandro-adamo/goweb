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
  <div class="col-md-6">
    <i class="fa fa-dashboard"></i> Dashboard1
  </div>
  <div class="col-md-2 col-md-offset-3">
      <select name="periodo" id="periodo" class="form-control">

        @for ($ano=2021;$ano>=2018;$ano--) {

          @if ($ano == date('Y'))

            @for ($mes=date('m');$mes>=1;$mes--) 
              <option value="{{$mes}}-{{$ano}}" @if ($ano == $ano2 and $mes == $mes2) selected="" @endif>{{getMes($mes)}} {{$ano}}</option>
            @endfor

          @else

            @for ($mes=12;$mes>=1;$mes--) 
              <option value="{{$mes}}-{{$ano}}" @if ($ano == $ano2 and $mes == $mes2) selected="" @endif>{{getMes($mes)}} {{$ano}}</option>
            @endfor
          @endif

        @endfor

      </select>

  </div>
  <div class="col-md-1">
    <button type="submit" class="btn btn-flat btn-default btn-block">Pesquisar</button>
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
  $id_perfil = \Auth::user()->id_perfil;
  echo ($id_perfil);

  if ($id_representante == '') {
    $id_representante = 0;
  }



  $representantes = Session::get('representantes');
 

   if ($id_perfil == '1' or $id_perfil == '2') {
    $representante = '';
    }
    else{
    $representante = 'id_rep in ('.$representantes.') and';
    }


  $vendas = array();

	$vendas = \DB::select("select sum(valor) as venda
							from vendas_jde 
							where $representante ano = $ano2
								and mes = $mes2
								and ult_status not in ('980','984') and tipo_item = 006");

  $vendas_grife = \DB::select("
			select grife, sum(qtde) as pecas, sum(valor) as valor
             from vendas_jde 
             left join itens on id_item = itens.id
             where $representante
                ano = $ano2
               and mes = $mes2
               and ult_status not in ('980','984') and tipo_item = 006
               group by grife");
 

  $cancelados = array();

  $cancelados = \DB::select("select sum(valor) as cancelados
              from vendas_jde 
              where $representante
                 ano = $ano2
                and mes = $mes2
                and ult_status in ('980','984') and tipo_item = 006");

$bloqueados = array();

  $bloqueados = \DB::select("select sum(valor) as bloqueados
              from vendas_jde vendas
			  left join suspensoes on vendas.pedido = suspensoes.pedido and suspensoes.tipo = vendas.tipo

              where 
              $representante
              ult_status not in ('980','984') and suspensoes.codigo is not null and ano = $ano2
                and mes = $mes2 and tipo_item = 006
                ");

$devolvidos = array();
  $devolvidos = \DB::select("select sum(dev.valor)*-1 devolvidos
              from devolucoes dev
				left join pedidos_jde ped on ped.pedido = dev.ped_original and ped.linha = dev.linha_original and dev.tipo_original = ped.tipo
                
				where dev.tipo_original = 'so'
				and ped.id_rep in ($representantes)
                and year(dev.dt_emissao) = $ano2
                and month(dev.dt_emissao) = $mes2
				and tipo_item = 006
                ");



$faturamentos = array();

  $faturamentos = \DB::select("select sum(total) as faturamento
              from notas_jde 
              left join itens on id_item = itens.id

              where 
                $representante codtipoitem = '006'
                and ano = $ano2
                and mes = $mes2
                -- and ult_status = '620' 
                and prox_status in (610,617,620,999)
                -- and prox_status = '999'");
  $orcamentos = array();

	// $orcamentos = \DB::select("select sum(valor) as orcamento
	// 						from vendas_jde 
	// 						where id_rep = $id_representante 
	// 							and year(dt_venda) = year(now()) 
	// 							and month(dt_venda) = month(now())
	// 							and ult_status = '510' 
	// 							and prox_status = '515'");
$carteira = array();

 // $carteira = \DB::select("select ifnull(sum(Inativo),0) as Inativo, ifnull(sum(Inadimplente),0) as Inadimplente, //ifnull(sum(Juridico),0) as Juridico, ifnull(sum(Ativo),0) as Ativo,
   //                   (ifnull(sum(Inativo),0) + ifnull(sum(Inadimplente),0) + ifnull(sum(Juridico),0) + ifnull(sum(Ativo),0) ) as total

     //         from(
       //         select 
         //         case when financeiro = 'CI' then count(*) end as 'Inativo',
           //       case when financeiro = 'IN' then count(*) end as 'Inadimplente',
             //     case when financeiro = 'JU' then count(*) end as 'Juridico',
              //    case when financeiro not in ('CI', 'IN', 'JU') then count(*) end as 'Ativo'
               // from (
                //  select cli
                 // from carteira
                 // where rep in ($representantes)
                  //group by cli
                //) as base
                //left join addressbook on cli = addressbook.id
                //group by financeiro
              //) as fim");

  $mostruarios = array();

  // $mostruarios = \DB::select("select id_rep,codgrife, grife, count(*) as itens, sum(venda) as itens_venda, (sum(venda)/count(*))*100 as perc,
  //                     case 
  //                       when ((sum(venda)/count(*))*100) > 70 then 'success'
  //                       when ((sum(venda)/count(*))*100) > 30 and ((sum(venda)/count(*))*100) < 70 then 'warning'
  //                       else 'danger' end as cor
  //               from (
  //                 select base.id_rep,codgrife, grife, base.id_item, base.item, 
  //                   case when sum(vda.qtde) > 0 then 1 else 0 end as venda
  //                 from (
  //                   select id_rep, codgrife, grife, id_item, item
  //                   from malas
  //                   left join itens on id_item = itens.id 
  //                   where 
  //                     id_rep  in ($representantes)
  //                     and codtipoitem = '006'
  //                     and local = 'MALA' 
  //                     and statusatual not in ('esgotado','EM PRODUÇÃO')
  //                     and clasmod in ('linha a','linha a++','linha a+','linha a-')
                      
  //                 ) as base
  //                 left join vendas_jde vda on vda.id_item = base.id_item and vda.ult_status not in ('984', '980') and vda.id_rep = base.id_rep and dt_venda > date_sub(now(), interval 30 day)
  //                 group by base.id_rep,codgrife, grife, base.id_item, base.item
  //               ) as fim
  //               group by id_rep,codgrife, grife");

  $dia1mes = date('Y').'-'.date('m').'-01';

  $financeiro = array();

//   $financeiro = \DB::select("
// select 
// sum(vlr_pago_sem_impostos) vlr_pago_sem_impostos,
// sum(vlr_vencido_sem_impostos) vlr_vencido_sem_impostos,
// sum(vlr_avencer_sem_impostos) vlr_avencer_sem_impostos

// from (

//   select *, 
//   case when dt_pagto is not null then valor_pago*(proporcao/100) else 0 end vlr_pago_com_impostos,
//   case when dt_pagto is not null then (valor_pago-impostos_parcela)*(proporcao/100) else 0 end vlr_pago_sem_impostos,
//   case when dt_vencimento < now() and dt_pagto is null then valor_parcela*(proporcao/100) else 0 end vlr_vencido_com_impostos,
//   case when dt_vencimento < now() and dt_pagto is null then (valor_parcela-impostos_parcela)*(proporcao/100) else 0 end vlr_vencido_sem_impostos,
//   case when dt_vencimento >= now() and month(dt_vencimento) = $mes2 and year(dt_vencimento) = $ano2 and dt_pagto is null then valor_parcela*(proporcao/100) else 0 end vlr_avencer_com_impostos,
//   case when dt_vencimento >= now() and month(dt_vencimento) = $mes2 and year(dt_vencimento) = $ano2 and dt_pagto is null then (valor_parcela-impostos_parcela)*(proporcao/100) else 0 end vlr_avencer_sem_impostos

//   from (
    
//     select *, (valor_pedido_rep / valor_pedido_total) * 100 as proporcao, (valor_parcelas_total - valor_pedido_total) / qtde_parcelas as impostos_parcela
//     from (
      
//       select titulo, parcela, documento, dup.ped_original, dup.dt_emissao, dt_vencimento, dt_pagto, status, situacao, valor_parcela, valor_pago, sum(ped.valor) as valor_pedido_rep,

//         (select sum(valor) from pedidos_jde ped2 where ped2.pedido = ped.pedido and ped2.tipo = 'SO' and ped2.ult_status not in ('984','980')) as valor_pedido_total,
//         (select sum(valor_parcela) from titulos dup2 where dup2.ped_original = ped.pedido and dup2.tipo_original = 'SO' ) as valor_parcelas_total,
//         (select count(*) from titulos dup2 where dup2.ped_original = ped.pedido and dup2.tipo_original = 'SO' ) as qtde_parcelas
        
//         from titulos dup
//         left join pedidos_jde ped on dup.ped_original = ped.pedido and dup.tipo_original = ped.tipo

//         where ((status <> 'P') or (year(dt_pagto) = $ano2 and month(dt_pagto) = $mes2  and valor_pago > 0))
//                 and ped.ult_status not in ('980','984') and dup.tipo = 'RI' and id_rep = $id_representante 
      
//             group by  titulo, parcela, documento, dup.ped_original, dup.dt_emissao, dt_vencimento, dt_pagto, status, situacao, valor_parcela, valor_pago
            
//     ) as fim1
//   ) as fim2
// ) as fim3");
@endphp

<div class="row">
<div class="col-lg-3 col-xs-6 col-md-3">

  <!-- small box -->
  <div class="small-box bg-aqua">

    <div title="Vendas no mês atual" class="inner">
      <h3>@if ($vendas) {{number_format($vendas[0]->venda,2,',','.')}} @endif</h3>

      <p>Vendas no mês</p>
    </div>
    <div class="icon">
      <i class="fa fa-shopping-cart"></i>
    </div>

    <a title="Vendas no mês atual" href="/vendas?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
	  
	  
<!-- ./col -->
<div class="col-lg-3 col-xs-6 col-md-3">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3>@if ($cancelados) {{number_format($cancelados[0]->cancelados,2,',','.')}} @endif</h3>

      <p>Cancelamentos no mês</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
    <a href="/vendas?ano={{date('Y')}}&mes={{date('m')}}&status=cancelado" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>	  
	  

	  
	  
<!-- ./col -->
<div class="col-lg-3 col-xs-6 col-md-3">
  <!-- small box -->
  <div class="small-box bg-green">
    <div title="Faturamento contempla vendas do mês + atendimento de orçamento de meses anteiores" class="inner">
      <h3>@if ($faturamentos) {{number_format($faturamentos[0]->faturamento,2,',','.')}} @endif</h3>

      <p>Faturamentos no mês</p>
    </div>
    <div class="icon">
      <i class="fa fa-barcode"></i>
    </div>
    <a href="/notas" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>

	  
	  
<!-- ./col -->
<div class="col-lg-3 col-xs-6 col-md-3">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3>@if ($devolvidos) {{number_format($devolvidos[0]->devolvidos,2,',','.')}} @endif</h3>

      <p>Devoluções no mês</p>
    </div>
    <div class="icon">
      <i class="fa fa-file-o"></i>
    </div>
    <a href="/devolucoes_det?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>

	  

	  
	  

	  
	  
	  
<!-- ./col -->
<div class="col-lg-3 col-xs-6 col-md-3">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
     <h3>@if ($bloqueados) {{number_format($bloqueados[0]->bloqueados,2,',','.')}} @endif</h3>

      <p>Bloqueados do mês</p>
    </div>
    <div class="icon">
      <i class="fa fa-file-o"></i>
    </div>
    <a href="/bloqueados_det?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
	  
	  
	  
<!-- ./col -->
</div>

<div class="row">
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-12">

        <div class="box box-widget">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i> Carteira</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="chart">
              <canvas id="myChart" style="height:230px"></canvas>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-green">@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Ativo/$carteira[0]->total)*100)}}% @endif</span>
                  <h5 class="description-header">@if ($carteira) {{$carteira[0]->Ativo}} @endif</h5>
                  <span class="description-text">ATIVO</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-yellow">@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Inativo/$carteira[0]->total)*100)}}% @endif</span>
                  <h5 class="description-header">@if ($carteira) {{$carteira[0]->Inativo}} @endif</h5>
                  <span class="description-text">INATIVO</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-green">@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Inadimplente/$carteira[0]->total)*100)}}% @endif</span>
                  <h5 class="description-header">@if ($carteira) {{$carteira[0]->Inadimplente}} @endif</h5>
                  <span class="description-text">INADIMPLENTE</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-xs-6">
                <div class="description-block">
                  <span class="description-percentage text-red">@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Juridico/$carteira[0]->total)*100)}}% @endif</span>
                  <h5 class="description-header">@if ($carteira) {{$carteira[0]->Juridico}} @endif</h5>
                  <span class="description-text">JURIDICO</span>
                </div>
                <!-- /.description-block -->
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>
    </div>
    <div class="row">

        <div class="col-md-12">
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-suitcase"></i> Mostruários</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Mala</th>
                    <th>Itens na mala</th>
                    <th>Itens com venda</th>
                    <th>Aproveitamento</th>
                  </tr>
                </thead>
                <tbody>

                @if ($mostruarios && count($mostruarios) > 0)
                  @foreach ($mostruarios as $most)
                  <tr>
                    <td valign="middle" align="center"><img src="/img/marcas/{{$most->grife}}.png" class="img-responsive" width="80" alt=""></td>
                    <td align="center"> {{$most->itens}}</td>
                    <td align="center"> {{$most->itens_venda}}</td>
                    <td>
                      
                      <div class="progress">
                        <div class="progress-bar progress-bar-{{$most->cor}}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$most->perc}}%">
                          <span class="text-black"> {{number_format($most->perc)}}%</span>
                        </div>
                      </div>

                    </td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="4" align="center"> Nenhuma mala disponível </td>
                  </tr>
                @endif
                </tbody>
              </table>
              <!-- /.users-list -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>
          <!--/.box -->
       </div>

    </div>
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
              
            </div>
          <!-- /.box-body -->
          </div>
          <div class="box-footer">
                <!-- TABELA AQUI -->
            </div>
          <!-- /.box-footer -->
        </div>
        <!--/.box -->
     </div>
  </div>

    </div>
    <div class="col-md-5">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Vendas Diarias</h3>

              <div class="box-tools pull-right">
                <a href="/dashboard/exportaExcel?id={{\Auth::user()->id_addressbook}}&ano={{$ano2}}&mes={{$mes2}}">Exporta Mapa</a>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="myChart2" style="height:230px"></canvas>
              </div>
            </div>
            <div class="box-footer">
                <div class="row">
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-green"><!-- @if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Ativo/$carteira[0]->total)*100)}}% @endif -->0</span>
                      <h5 class="description-header"><!-- @if ($carteira) {{$carteira[0]->Ativo}} @endif-->0</h5>
                      <span class="description-text">TICKET MD</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-yellow"><!-- @if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Inativo/$carteira[0]->total)*100)}}% @endif--> 0</span>
                      <h5 class="description-header"><!-- @if ($carteira) {{$carteira[0]->Inativo}} @endif --> 0</h5>
                      <span class="description-text">PDVS ATENDIDOS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-green"><!--@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Inadimplente/$carteira[0]->total)*100)}}% @endif-->0</span>
                      <!-- <h5 class="description-header">@if ($carteira) {{$carteira[0]->Inadimplente}} @endif</h5> -->
						          <h5 class="description-header">0</h5>
                      <span class="description-text">DIAS POSITIVADOS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block">
                      <span class="description-percentage text-red"><!--@if ($carteira && $carteira[0]->total > 0) {{number_format(($carteira[0]->Juridico/$carteira[0]->total)*100)}}% @endif-->0</span>
                      <h5 class="description-header"><!--@if ($carteira) {{$carteira[0]->Juridico}} @endif-->0</h5>
                      <span class="description-text">?</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
          </div>
        </div>
      </div>

      <div class="row">

          <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-suitcase"></i> Vendas por Grife</h3>

              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Grife</th>
                      <th>Peças</th>
                      <th>Valor</th>
                    </tr>
                  </thead>
                  <tbody>

                  @if (isset($vendas_grife) && $vendas_grife && count($vendas_grife) > 0)
                    @foreach ($vendas_grife as $grife)
                    <tr>
                      <td valign="middle" align="center">{{$grife->grife}}</td>
                      <td align="center"> {{number_format($grife->pecas,0,'.','.')}}</td>
                      <td align="right"> {{number_format($grife->valor,2,',','.')}}</td>

                    </tr>
                    @endforeach
                  @endif
                  </tbody>
                </table>
                <!-- /.users-list -->
              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->
            </div>
            <!--/.box -->
         </div>

      </div>


      <div class="row">


        <div class="col-md-12">
          @if (isset($financeiro[0]->vlr_avencer_sem_impostos))
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-money"></i> Financeiro</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Valor</th>
                  </tr>
                </thead>
                <tbody>

                  <tr class="warning">
                    <td>A VENCER EM MARCO 2020</td>
                    <td align="right">{{number_format($financeiro[0]->vlr_avencer_sem_impostos,2,',', '.')}}</td>
                  </tr>

                  <tr class="success">
                    <td>PAGOS NO MÊS {{$mes2}} / {{$ano2}}</td>
                    <td align="right">{{number_format($financeiro[0]->vlr_pago_sem_impostos,2,',', '.')}}</td>
                  </tr>

                  <tr class="danger">
                    <td>VENCIDOS ATÉ ONTEM</td>
                    <td align="right">{{number_format($financeiro[0]->vlr_vencido_sem_impostos,2,',', '.')}}</td>
                  </tr>

                </tbody>
              </table>
              <!-- /.users-list -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
            @endif
          </div>
          <!--/.box -->


        </div>
      </div>

    </div>
</div>

<pre>

{{$representantes}}
</pre>

@stop