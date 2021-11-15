@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard
@append 

@section('conteudo')


@php
        $sql = '';

  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');

@endphp

     
<div class="row">
  <div class="col-md-6">

	  @php
      $titulo = '';
      $query = array();
      $id_perfil = \Auth::user()->id_perfil;

      $representantes = Session::get('representantes');
      $grifes = Session::get('grifes');

      $sql_search = '';
      if (isset($_GET["search"]) && $_GET["search"] <> '') {
        $search = $_GET["search"];
        $sql_search = " where nome like '%$search%' ";
      }

      if (isset($_GET["cliente"]) && $_GET["cliente"] <> '') {

        $id_usuario = \Auth::user()->id_addressbook;
        $cliente = $_GET["cliente"];
        $titulo = $cliente;

        $query = \DB::select("select *,  ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.nome order by ano desc, mes desc limit 1  ) as ult_data from (
        
        select cliente as nome, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12,
        sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12,
    0 grifes18, 0 meses18, 0 qtde18, 0 valor18
    from (
    select  cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select  ab.id, CONCAT( ab.id, ' - ' , ab.razao) as cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      left join addressbook ab on vendas_cml.cliente = ab.cliente
      where vendas_cml.tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') /* and repres = $id_usuario */ and vendas_cml.cliente = '$cliente' 
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
    
union all
 
  select cliente, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12,
    count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18 from (
    select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select ab.id, CONCAT( ab.id, ' - ' , ab.razao) as cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      left join addressbook ab on vendas_cml.cliente = ab.cliente
      where vendas_cml.tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') /* and repres = $id_usuario */ and vendas_cml.cliente = '$cliente' 
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
) as sele3 group by cliente
) as sele4 

order by ult_data asc");

      } elseif (isset($_GET["id_diretor"]) && $_GET["id_diretor"] <> '') {

        $id_diretor = $_GET["id_diretor"];
        $titulo = 'Diretor '.$id_diretor;

        $query = \DB::select("select *,  ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.nome order by ano desc, mes desc limit 1  ) as ult_data from (
        
        select cliente as nome, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12,
        sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12,
    0 grifes18, 0 meses18, 0 qtde18, 0 valor18
    from (
    select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct vendas_cml.cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      left join addressbook ab on vendas_cml.cliente = ab.cliente
      where vendas_cml.tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and id_diretor = $id_diretor
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
    
union all
 
  select cliente, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12,
    count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18 from (
    select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct vendas_cml.cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml 
      left join addressbook ab on vendas_cml.cliente = ab.cliente
      where vendas_cml.tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and id_diretor = $id_diretor
    ) as sele1 group by cliente, grife_jde
  ) as sele2 group by cliente
) as sele3 group by cliente
) as sele4 
$sql_search
order by ult_data asc");

      } elseif (isset($_GET["id_supervisor"]) && $_GET["id_supervisor"] <> '') {

        $id_supervisor = $_GET["id_supervisor"];
        $titulo = 'Representantes '.$id_supervisor;

        $sql = ' and supervisor = '.$id_supervisor;

        $query = \DB::select("select sele5.*, ab.fantasia as nome, '' as ult_data from (

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
left join addressbook ab on ab.id = repres");

      } else {

//       if ($id_perfil == 1) {

//         $titulo = 'Diretores';


//         $query = \DB::select("select sele5.*, ab.fantasia as nome, '' as ult_data from (

// select diretor, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

//   select diretor, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
//     select diretor, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
//     from (
//       select diretor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct diretor, cliente, grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') 
//       ) as sele1 group by diretor, cliente, grife_jde
//     ) as sele2 group by diretor, cliente
//   ) as sele3 group by diretor

//   union all
   
//   select diretor, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
//     select diretor, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
//     from (
//       select diretor, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct diretor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')  
//       ) as sele1 group by diretor, cliente, grife_jde
//     ) as sele2 group by diretor, cliente
//   ) as sele3  group by diretor
// ) as sele4  group by diretor

// ) as sele5
// left join addressbook ab on ab.id = diretor");


//       }

//       if ($id_perfil == 4) {

//         $id_usuario = \Auth::user()->id_addressbook;

//         $titulo = 'Clientes';

//         $query = \DB::select("
//         select *,  ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.nome order by ano desc, mes desc limit 1  ) as ult_data from (
        
//         select cliente as nome, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12,
//         sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

//   select cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12,
//     0 grifes18, 0 meses18, 0 qtde18, 0 valor18
//     from (
//     select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//       select distinct cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
//       from vendas_cml 
//       where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and repres = $id_usuario
//     ) as sele1 group by cliente, grife_jde
//   ) as sele2 group by cliente
    
// union all
 
//   select cliente, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12,
//     count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18 from (
//     select cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//       select distinct cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
//       from vendas_cml 
//       where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') and repres = $id_usuario
//     ) as sele1 group by cliente, grife_jde
//   ) as sele2 group by cliente
// ) as sele3 group by cliente
// ) as sele4 
// $sql_search
// order by ult_data asc
// ");


//       }


//       if ($id_perfil == 5) {

//         $titulo = 'Supervisores';

//         $id_usuario = \Auth::user()->id_addressbook;

//         $sql = ' and diretor = '.$id_usuario;


//         $query = \DB::select("
// select sele5.*, ab.fantasia as nome, '' as ult_data 
// from (

// select supervisor, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

//   select supervisor, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
//     select supervisor, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
//     from (
//       select supervisor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct supervisor, cliente, grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
//       ) as sele1 group by supervisor, cliente, grife_jde
//     ) as sele2 group by supervisor, cliente
//   ) as sele3 group by supervisor

//   union all
   
//   select supervisor, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
//     select supervisor, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
//     from (
//       select supervisor, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct supervisor, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql 
//       ) as sele1 group by supervisor, cliente, grife_jde
//     ) as sele2 group by supervisor, cliente
//   ) as sele3  group by supervisor
// ) as sele4  group by supervisor

// ) as sele5
// left join addressbook ab on ab.id = supervisor");
//       }


//       if ($id_perfil == 6) {
//         $id_usuario = \Auth::user()->id_addressbook;


//         $titulo = 'Representantes'; 

//         $sql = ' and supervisor = '.$id_usuario;
//         echo $sql;

//         $query = \DB::select("select sele5.*, ab.fantasia as nome, '' as ult_data from (

// select repres, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

//   select repres, avg(grifes12) grifes12, avg(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12, 0 grifes18, 0 meses18, 0 qtde18, 0 valor18 from (
//     select repres, cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12
//     from (
//       select repres, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct repres, cliente, grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
//       ) as sele1 group by repres, cliente, grife_jde
//     ) as sele2 group by repres, cliente
//   ) as sele3 group by repres

//   union all
   
//   select repres, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12, avg(grifes18) grifes18, avg(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (
//     select repres, cliente, count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18
//     from (
//       select repres, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
//         select distinct repres, cliente,  case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
//         from vendas_cml 
//         where tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')  $sql
//       ) as sele1 group by repres, cliente, grife_jde
//     ) as sele2 group by repres, cliente
//   ) as sele3  group by repres
// ) as sele4  group by repres

// ) as sele5
// left join addressbook ab on ab.id = repres
//");

//       }



    }
    @endphp

<form action="" method="get">
    <div class="box box-widget">
      <div class="box-header with-border">
			  <h3 class="box-title">{{$titulo}}</h3>
        <span class="pull-right">
          @if ($titulo == 'Clientes')
            <a href="/dashboard/exportaClientes?id={{$id_usuario}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta</a>
          @else
            <a href="/dashboard/exportaClientes" class="pull-right"><i class="fa fa-file-o"></i> Exporta</a>
          @endif
        </span>
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-9">
            <input type="text" class="form-control" placeholder="" autofocus  name="search">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-flat btn-block btn-default">Buscar</button>
          </div>
        </div>
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
              <td><a href="?cliente={{$a->nome}}"><i class="fa fa-plus-square"> </i></a> {{$a->nome}}</td>
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
            </tr>


          @endforeach 
        </table>
        </h6>
      </div>
    </div>
</form>

  </div>
</div>

@stop