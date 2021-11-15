@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')

@php
  $id_rep = \Auth::user()->id_addressbook;
//   $malas = \DB::select("select rep, codgrife, grife, sum(pecas) as pecas, sum(disp15d) as disp15d,  sum(prod) as prod,  sum(disp30d) as disp30d,  sum(imediato) as imediato,  sum(esgotado) as esgotado
// from (
//   select rep, codgrife, grife, sum(pecas) as pecas,
//     case when ultstatus = 'DISPONÍVEL EM 15 DIAS' then sum(pecas) else 0 end as 'disp15d',
//     case when ultstatus = 'EM PRODUÇÃO' then sum(pecas) else 0 end as 'prod',
//     case when ultstatus = 'DISPONÍVEL EM 30 DIAS' then sum(pecas) else 0 end as 'disp30d',
//     case when ultstatus = 'ENTREGA IMEDIATA' then sum(pecas) else 0 end as 'imediato',
//     case when ultstatus = 'ESGOTADO' then sum(pecas) else 0 end as 'esgotado'

//   from (
//     select rep, base.codgrife, itens.grife, sum(qtde) as pecas , itens.ultstatus
//     from (
//       select rep, grife as codgrife, valor as grife
//       from carteira 
//       left join caracteristicas on campo = 'grife' and codigo = grife
//       where rep = $id_rep
//       group by rep, grife, valor
//     ) as base
//     left join mostruarios2 on id_rep = base.rep and local = 'MALA'
//     left join itens on itens.id = mostruarios2.id_item
//     where base.codgrife = itens.codgrife
//     group by rep, codgrife, itens.grife,itens.ultstatus
    
//   ) as fim1

//   group by rep, codgrife, grife,ultstatus
// ) as fim2
// group by rep, codgrife, grife"); 

  $malas = \DB::select("select rep, codgrife, grife, sum(pecas) as pecas, 
  sum(disp15d) as disp15d,  sum(prod) as prod,  sum(disp30d) as disp30d,  sum(imediato) as imediato,  sum(esgotado) as esgotado,
  sum(vda_disp15d) as vda_disp15d,  sum(vda_prod) as vda_prod,  sum(vda_disp30d) as vda_disp30d,  sum(vda_imediato) as vda_imediato,  sum(vda_esgotado) as vda_esgotado
from (
  select rep, codgrife, grife, sum(pecas) as pecas,
    case when ultstatus = 'DISPONÍVEL EM 15 DIAS' then sum(pecas) else 0 end as 'disp15d',
    case when ultstatus = 'EM PRODUÇÃO' then sum(pecas) else 0 end as 'prod',
    case when ultstatus = 'DISPONÍVEL EM 30 DIAS' then sum(pecas) else 0 end as 'disp30d',
    case when ultstatus = 'ENTREGA IMEDIATA' then sum(pecas) else 0 end as 'imediato',
    case when ultstatus = 'ESGOTADO' then sum(pecas) else 0 end as 'esgotado',

    case when ultstatus = 'DISPONÍVEL EM 15 DIAS' then sum(venda) else 0 end as 'vda_disp15d',
    case when ultstatus = 'EM PRODUÇÃO' then sum(venda)  else 0 end as 'vda_prod',
    case when ultstatus = 'DISPONÍVEL EM 30 DIAS' then sum(venda)  else 0 end as 'vda_disp30d',
    case when ultstatus = 'ENTREGA IMEDIATA'   then sum(venda)  else 0 end as 'vda_imediato',
    case when ultstatus = 'ESGOTADO'   then sum(venda) else 0 end as 'vda_esgotado'
        

  from (
    select rep, codgrife, grife,  pecas , fim0.id_item , ultstatus , case when sum(qtde) > 0 then 1 else 0 end as venda
        from (
      select rep, base.codgrife, itens.grife,  itens.id as id_item, itens.ultstatus , qtde as pecas 
      from (
        select rep, grife as codgrife, valor as grife
        from carteira 
        left join caracteristicas on campo = 'grife' and codigo = grife
        where rep = $id_rep
        group by rep, grife, valor
      ) as base
      left join mostruarios2 on id_rep = base.rep and local = 'MALA'
      left join itens on itens.id = mostruarios2.id_item
      where base.codgrife = itens.codgrife
      -- group by rep, codgrife, itens.grife,itens.ultstatus,itens.secundario,itens.id
    ) as fim0 
        left join vendas_jde on vendas_jde.id_item = fim0.id_item and vendas_jde.id_rep = fim0.rep and   dt_venda > date_sub(date(now()), INTERVAL 90 DAY)
        group by rep, codgrife, grife, fim0.id_item , ultstatus, pecas
  ) as fim1

  group by rep, codgrife, grife,ultstatus
) as fim2
group by rep, codgrife, grife");
@endphp

  <div class="row">

    @foreach ($malas as $mala)

      @php
        if ($mala->vda_imediato > 0 && $mala->imediato > 0) {
          $percentual = ceil(($mala->vda_imediato / $mala->imediato) * 100);
        } else {
          $percentual = 0;
        }

        if ($percentual < 30) {
          $cor = 'progress-bar-danger';
        } elseif ($percentual > 30 and $percentual < 60) {
          $cor = 'progress-bar-warning';
        } else {
          $cor = 'progress-bar-success';          
        }
      @endphp
      <div class="col-md-3">
        <div class="box box-body box-widget" align="center">
          <i class="fa fa-suitcase fa-5x"></i><br>
          <span> {{$mala->grife}} </span>


          <table class="table table-condensed table-bordered">
            <tr>
              <td width="20%" align="center"><i class="fa fa-check text-green"></i></td>
              <td width="20%" align="center"><i class="fa fa-calendar text-orange"></i></td>
              <td width="20%" align="center"><i class="fa fa-calendar text-orange"></i></td>
              <td width="20%" align="center"><i class="fa fa-industry text-purple"></i></td>
              <td width="20%" align="center"><i class="fa fa-close text-red"></i></td>
            </tr>
            <tr>
              <td width="20%" align="center">{{$mala->imediato}}</td>
              <td width="20%" align="center">{{$mala->disp15d}}</td>
              <td width="20%" align="center">{{$mala->disp30d}}</td>
              <td width="20%" align="center">{{$mala->prod}}</td>
              <td width="20%" align="center">{{$mala->esgotado}}</td>
            </tr>
          </table>
          <div class="progress-xxs">


            <div class="progress-bar {{$cor}} progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentual}}%">
              <span class="sr-only">40% Complete (success)</span>
            </div>
          </div>
        </div>

      </div>
    @endforeach
  </div>


@stop