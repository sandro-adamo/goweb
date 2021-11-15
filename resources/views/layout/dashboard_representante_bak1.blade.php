@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard
@append 

@section('conteudo')
@if (\Auth::user()->id_perfil == 12 or \Auth::user()->id_perfil == 16) 
@else

@php
      if (\Auth::viaRemember()) {
        echo 'teste1';
      }

      if (Auth::check()) {
        //echo 'teste2';
      }
      if (Auth::viaRemember()) {
        echo 'teste3';
      }


        $sql = '';

  $representantes = Session::get('representantes');

  //print_r($representantes);
  $grifes = Session::get('grifes');
//dd($representantes);


//   $dados = \DB::select("
// select avg(grifes12) grifes12, avg(grifes18) grifes18,  avg(meses12) meses12, avg(meses18) meses18 from (

// select carteira.*, grifes13, meses18, grifes18, meses13 from (
// select  cliente, rep
// from carteira
// left join addressbook ab on ab.id = carteira.cli
// where carteira.rep in ($representantes) and grife in $grifes
// group by cliente, rep
// ) as carteira


// left join (
// select  cliente, count(grife_jde) grifes13 from (
// select grife_jde, cliente
// from vendas_13meses v13
// left join addressbook ab on ab.id = v13.cli_jde
// where grife_jde in $grifes
// group by grife_jde, cliente
// ) as sele1
// group by cliente
// ) as vda13
// on vda13.cliente = carteira.cliente 

// left join (
// select  cliente, count(grife_jde) grifes18 from (
// select grife_jde, cliente
// from vendas_2018 v18
// left join addressbook ab on ab.id = v18.cli_jde
// where grife_jde in $grifes
// group by grife_jde, cliente
// ) as sele1
// group by cliente
// ) as vda18
// on vda18.cliente = carteira.cliente 


// left join (
// select cliente, avg(meses) meses13 from (
// select cliente, grife_jde, count(mes) meses from ( 
// select cliente, mes, grife_jde
// from vendas_13meses v13 
// left join addressbook ab on ab.id = v13.cli_jde
// where grife_jde in $grifes
// group by cliente, mes, grife_jde  ) as sele1
// group by cliente, grife_jde ) as sele2 
// group by cliente ) as freq13
// on freq13.cliente = carteira.cliente 


// left join (
// select cliente, avg(meses) meses18 from (
// select cliente, grife_jde, count(mes) meses from ( 
// select cliente, mes, grife_jde
// from vendas_2018 v18 
// left join addressbook ab on ab.id = v18.cli_jde
// where grife_jde in $grifes
// group by cliente, mes, grife_jde  ) as sele1
// group by cliente, grife_jde ) as sele2 
// group by cliente  ) as freq18
// on freq18.cliente = carteira.cliente 

// ) as fim
// ");

@endphp

{{-- 

     <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>@if ($dados[0]->meses12 > $dados[0]->meses18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif {{number_format($dados[0]->meses12,1)}}</h3>

              <p>Frequência</p>

				
            </div>
            <div class="icon">
              <i class="fa fa-line-chart"></i>
            </div>
            <a href="/comercial/frequencia/" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3>@if ($dados[0]->grifes12 > $dados[0]->grifes18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif {{number_format($dados[0]->grifes12,1)}} </h3>

              <p>Grife</p>
            </div>
            <div class="icon">
              <i class="fa fa-tag"></i>
            </div>
            <a href="/comercial/grifes/" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
 --}}



<div class="row">
  <div class="col-md-6">

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Grifes de clientes atendidos</h3>
		  				
<span class="pull-right" style="margin-left: 20px;"><a href="/dashboard/exportaExcel?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Mapa</a></span>
<span class="pull-right"><a href="/dashboard/exportaClientes?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Carteira</a></span>
				
      </div>

      <div class="box-body">
        <div class="row">

          <div class="col-md-8">

            <div class="row">
              <div class="col-md-6">
                <a href="/comercial/frequencia/representantes"><div id="chart1"></div></a>
              </div>
              <div class="col-md-6">
                <div id="chart"></div>
              </div>
            </div>

              @php

                $id_usuario = \Auth::user()->id_addressbook;

                if (\Auth::user()->id_perfil == 5) {

                  $sql = ' and coddir = '.$id_usuario;
			  

                }


                if (\Auth::user()->id_perfil == 6) {

                  $sql = ' and codsuper = '.$id_usuario;			  
                }

                if (\Auth::user()->id_perfil == 4) {

                  $sql = ' and id_rep = '.$id_usuario;

                }
			  
		 $qtdegrifes1 = \DB::select(" 
			select codgrife, count(cliente) clientes, sum(pdvs) pdvs, sum(qtde) qtde from (
				select codgrife, cliente, count(id_cliente) pdvs, sum(qtde) qtde from (
                    select case when codgrife in ('go','at') then 'AT' when codgrife in ('EV','NG') then 'EV' else codgrife end as codgrife, id_cliente, cliente, sum(qtde) qtde
					from vendas_jdes vds
                    left join addressbook ab on ab.id = id_cliente
                    where ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5') and datediff(now(),dt_venda) <= 365
					and id_rep in ($representantes)
																																				
					group by codgrife, id_cliente, cliente
				) as fim0 group by codgrife, cliente
			) as fim1 group by codgrife order by codgrife
		");
			  
			  



                $total = count($qtdegrifes1);
                $index = 0;

              @endphp
              <h6>
              <table class="table table-bordered table-condensed" style="font-size: 11px";>
				<tr>PDVs dos ultimos 12 meses</tr>
                <tr>
                  <th></th>
                  <th style="text-align: center">Clientes</th>
				  <th style="text-align: center">PDVs</th>
                  <th style="text-align: center">Peças</th>
                  <th></th>
                  <th style="text-align: center">Clientes</th>
				  <th style="text-align: center">PDVs</th>
                  <th style="text-align: center">Peças</th>
                </tr>
              @foreach ($qtdegrifes1 as $a)

                @if ($index == 0)
                  <tr>
                @elseif ($index == 2) 
                  </tr>
                
                  @php
                    $index = 0;
                  @endphp
                  
                @endif 

                  <td align="center" class="text-bold"><a href="/clientes_det?codgrife={{$a->codgrife}}">{{$a->codgrife}}</a></td>
                  <td align="center">{{number_format($a->clientes,0, '.','.')}}</td>
				  <td align="center">{{number_format($a->pdvs,0, '.','.')}}</td>
                  <td align="center">{{number_format($a->qtde,0, '.','.')}}</td>
                
                @php
                  $index++;
                @endphp
                
              @endforeach
              </table>
              </h6>

          </div>
          <div class="col-md-4">  
            <table class="table table-bordered table-condensed" style="font-size: 11px;">
              <tr>
                <td>grifes_A</td>
                <td>2018</td>
                <td>12meses</td>
              </tr>         

              @php


                $id_usuario = \Auth::user()->id_addressbook;

                if (\Auth::user()->id_perfil == 5) {

                  $sql = ' and diretor = '.$id_usuario;

                }


                if (\Auth::user()->id_perfil == 6) {

                  $sql = ' and supervisor = '.$id_usuario;

                }

                if (\Auth::user()->id_perfil == 4) {

                  $sql = ' and repres = '.$id_usuario;

                }



$grifecli = \DB::select("/**quantidade de pdvs por frequencia de grifes **/
select grifes, sum(ind12) ind12, sum(ind18) ind18 from (
  select grifes, 
    case when tipo = '12m' then cliente else 0 end as ind12,
    case when tipo = '2018' then cliente else 0 end as ind18
    from (
    
      select  tipo, grifes, count(cliente) cliente from (        
      select tipo, cliente, count(grife_jde) grifes from (
        select tipo, cliente, grife_jde
          from vendas_cml
            where tipo in ('12m', '2018') $sql
        group by tipo, cliente, grife_jde
      ) as sele1
            group by tipo, cliente
    ) as sele2 group by tipo, grifes
  ) as sele3 group by grifes, tipo
) as sele4 group by grifes");

                $grifes18 = 0;
                $grifes12 = 0;
              @endphp



              @foreach ($grifecli as $a)

                @php
                  $cor = '';
                  if ($a->grifes <= 3) {
                    $cor = 'bg-red';
                  } elseif ($a->grifes > 3 and $a->grifes <=5) {
                    $cor = 'bg-yellow';
                  } elseif ($a->grifes > 5) {
                    $cor = 'bg-green';
                  }

                  $grifes18 += $a->ind18;
                  $grifes12 += $a->ind12;

                @endphp

                <tr>
                  <td align="center" class="{{$cor}}">{{$a->grifes}}</td>
                  <td align="center">{{$a->ind18}}</td>
                  <td align="center">{{$a->ind12}}</td>
                </tr>      
              @endforeach     

              <tr>
                <td align="center" class="text-bold">TOTALdddd</td>
                <td align="center" class="text-bold">{{$grifes18}}</td>
                <td align="center" class="text-bold">{{$grifes12}}</td>
              </tr>

            </table>
          </div>
        </div>
      </div>

    </div>

{{-- 	
	
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Frequência de compras por grife</h3>
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-6">  
            <div id="chart3"></div>
          </div>
          <div class="col-md-6">
            <div id="chart2"></div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-12">  
            <div id="chart5"></div>
          </div>
        </div>
      </div>
    </div>  


@php

  $id_usuario = \Auth::user()->id_addressbook;

    if (\Auth::user()->id_perfil == 5) {

      $sql = ' and id_diretor = '.$id_usuario;

    }


    if (\Auth::user()->id_perfil == 6) {

      $sql = ' and id_supervisor = '.$id_usuario;

    }

    if (\Auth::user()->id_perfil == 4) {

      $sql = ' and id_representante = '.$id_usuario;

    } 

  $orcaberto = \DB::select("select sum(AH) AH, SUM(AT) AT, SUM(BG) BG, SUM(EV) EV, SUM(HI) HI, SUM(JO) JO, SUM(SP) SP, SUM(TC) TC from (
  select 
    case when grife = 'AH' then qtde else 0 end as AH,
        case when grife = 'AT' then qtde else 0 end as AT,
        case when grife = 'BG' then qtde else 0 end as BG,
        case when grife = 'EV' then qtde else 0 end as EV,
        case when grife = 'HI' then qtde else 0 end as HI,
        case when grife = 'JO' then qtde else 0 end as JO,
        case when grife = 'SP' then qtde else 0 end as SP,
        case when grife = 'TC' then qtde else 0 end as TC from (
    
    select grife,sum(qtd_aberto) qtde from (
      select oa.*, month(dt_pedido) as mes, id_diretor, id_supervisor, itens.secundario, 
        case when itens.grife like 'evok%' then 'EV' else itens.codgrife end as grife
      from orcamentos_anal oa
      left join addressbook abr on abr.id = oa.id_representante
      left join itens on itens.id = oa.id_item
            where codgrife in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
    ) as sele1 group by grife
  ) as sele2
) as sele3");
  $total_orc = $orcaberto[0]->AH +  $orcaberto[0]->AT +  $orcaberto[0]->BG +  $orcaberto[0]->EV +  $orcaberto[0]->HI +  $orcaberto[0]->JO +  $orcaberto[0]->SP + $orcaberto[0]->TC;
@endphp

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Orçamentos abertos por mês</h3>
      </div>

      <div class="box-body">

        <div class="row">
          <div class="col-md-12">
            <h6>
            <table class="table table-condensed table-bordered" style="text-align: center">
              <tr>
                <th width="11%" style="text-align: center;">AH</th>
                <th width="11%" style="text-align: center;">AT</th>
                <th width="11%" style="text-align: center;">BG</th>
                <th width="11%" style="text-align: center;">EV</th>
                <th width="11%" style="text-align: center;">HI</th>
                <th width="11%" style="text-align: center;">JO</th>
                <th width="11%" style="text-align: center;">SP</th>
                <th width="11%" style="text-align: center;">TC</th>
                <th width="12%" style="text-align: center;">TOTAL</th>
              </tr>

              <tr>
                <td>{{$orcaberto[0]->AH}}</td>
                <td>{{$orcaberto[0]->AT}}</td>
                <td>{{$orcaberto[0]->BG}}</td>
                <td>{{$orcaberto[0]->EV}}</td>
                <td>{{$orcaberto[0]->HI}}</td>
                <td>{{$orcaberto[0]->JO}}</td>
                <td>{{$orcaberto[0]->SP}}</td>
                <td>{{$orcaberto[0]->TC}}</td>
                <td class="text-bold">{{$total_orc}}</td>
              </tr>
            </table>
          </h6>

          </div>
        </div>


        <div class="row">
          <div class="col-md-12">  
            <div id="chart15"></div>
          </div>
        </div>
      </div>
    </div>  


    


  </div>
  <div class="col-md-6">

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Clientes da grife por mês</h3>
      </div>

      <div class="box-body">
        <div id="chart9"></div>        
      </div>
    </div>  


    @php
      $titulo = '';
      $lista = '';
      $query = array();
      $id_perfil = \Auth::user()->id_perfil;

      $representantes = Session::get('representantes');
      $grifes = Session::get('grifes');


      if ($id_perfil == 1) {

        $titulo = 'Diretores';

        $lista = 'id_supervisor';

        $query = \DB::select("select sele5.*,ab.id, ab.fantasia as nome, '' as ult_data from (

select diretor, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select diretor, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
    select diretor, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
    from (
      select diretor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct diretor, cliente, grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') 
      ) as sele1 group by diretor, cliente, grife_jde
    ) as sele2 group by diretor, cliente
  ) as sele3 group by diretor

  union all
   
  select diretor, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
    select diretor, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
    from (
      select diretor, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct diretor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')  
      ) as sele1 group by diretor, cliente, grife_jde
    ) as sele2 group by diretor, cliente
  ) as sele3  group by diretor
) as sele4  group by diretor

) as sele5
left join addressbook ab on ab.id = diretor");


      }

      if ($id_perfil == 4) {

        $id_usuario = \Auth::user()->id_addressbook;

        $titulo = 'Top 10 Clientes';

        $lista = 'cliente';

        $query = \DB::select("
        select *, '0' as id,  ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.nome order by ano desc, mes desc limit 1  ) as ult_data
        
        from (
        
        select cliente as nome, sum(grifes12) grifes12, sum(meses12)meses12, sum(qtde12) qtde12, sum(valor12) valor12,
        sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12,
    0 grifes18, 0 meses18, 0 qtde18, 0 valor18
    from (
    select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and repres = $id_usuario
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
    
union all
 
  select cliente, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12,
    count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18 from (
    select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and repres = $id_usuario
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
) as sele3 group by cliente
) as sele4 order by ult_data asc
limit 10");


      }


      if ($id_perfil == 5) {

        $titulo = 'Supervisores';

        $id_usuario = \Auth::user()->id_addressbook;

        $sql = ' and diretor = '.$id_usuario;

        $lista = 'supervisor';


        $query = \DB::select("
select sele5.*, ab.id, ab.fantasia as nome, '' as ult_data 
from (

select supervisor, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select supervisor, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
    select supervisor, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
    from (
      select supervisor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct supervisor, cliente, grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
      ) as sele1 group by supervisor, cliente, grife_jde
    ) as sele2 group by supervisor, cliente
  ) as sele3 group by supervisor

  union all
   
  select supervisor, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
    select supervisor, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
    from (
      select supervisor, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct supervisor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql 
      ) as sele1 group by supervisor, cliente, grife_jde
    ) as sele2 group by supervisor, cliente
  ) as sele3  group by supervisor
) as sele4  group by supervisor

) as sele5
left join addressbook ab on ab.id = supervisor");
      }


      if ($id_perfil == 6) {

        $lista = 'representante';

        $titulo = 'Representantes'; 

        $sql = ' and supervisor = '.$id_usuario;
        echo $sql;

        $query = \DB::select("select sele5.*, ab.id, case when ab.nome = '' then ab.fantasia else ab.nome end as nome, '' as ult_data from (

select repres, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select repres, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
    select repres, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
    from (
      select repres, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct repres, cliente, grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
      ) as sele1 group by repres, cliente, grife_jde
    ) as sele2 group by repres, cliente
  ) as sele3 group by repres

  union all
   
  select repres, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
    select repres, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
    from (
      select repres, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
        select distinct repres, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
        from vendas_cml 
        where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')  $sql
      ) as sele1 group by repres, cliente, grife_jde
    ) as sele2 group by repres, cliente
  ) as sele3  group by repres
) as sele4  group by repres

) as sele5
left join addressbook ab on ab.id = repres
limit 20");

      }
    @endphp


    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">{{$titulo}} 
        	<small><a href="/dashboard/db_cliente"> ver todos</a></small>
        </h3>
        <span class="pull-right" style="margin-left: 20px;"><a href="/dashboard/exportaExcel?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Mapa</a></span>
        <span class="pull-right"><a href="/dashboard/exportaClientes?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Carteira</a></span>
      </div>

      <div class="box-body">
        <h6>
        <table class="table table-bordered table-condensed">
          <tr>
            <th rowspan="2">Nome</th>
            <th colspan="2">Grife</th>
            <th colspan="2">Frequência</th>
          </tr>
          <tr>
            <th align="center">2018</th>
            <th align="center">12M</th>
            <th align="center">2018</th>
            <th align="center">12M</th>
            <th align="center">Ult data</th>
          </tr>
          @foreach ($query as $a)

            <tr>
			  <td><a href="/dashboard/db_cliente"><a href="/dashboard/db_cliente?{{$lista}}={{$a->id}}"><i class="fa fa-plus-square"> </i></a> {{$a->nome}}</a></td>
              <td align="center">{{number_format($a->grifes18,2)}}</td>
              <td align="center">{{number_format($a->grifes12,2)}}@if ($a->grifes12 == $a->grifes18)  @elseif ($a->grifes12 > $a->grifes18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif</td>
              <td align="center">{{number_format($a->meses18,2)}}</td>
              <td align="center">{{number_format($a->meses12,2)}}@if ($a->meses12 == $a->meses18)  @elseif ($a->meses12 > $a->meses18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif</td>
              
              <td align="center">
              	@if 	(0==0){{$a->ult_data}}  
              	@elseif (0==0){{$a->ult_data}} 
              	@else 	<i class="fa fa-sort-desc text-red"></i> 
              	@endif
              </td>

              <td align="center"><a href="/dashboard/exportaClientes?id={{$a->id}}"><i class="fa fa-file-o"></i></a></td>
            </tr>
          @endforeach 
        </table>
        </h6>
        <div align="center"><a href="/dashboard/db_cliente"> ver todos</a></div>
      </div>
    </div>

--}} 
  </div>
</div>
	  
	  @endif

@stop