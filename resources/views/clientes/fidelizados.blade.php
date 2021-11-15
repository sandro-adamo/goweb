@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Clientes @if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
@append 

@section('conteudo')

@php
    $representantes = Session::get('representantes');
	$grife 			= $_GET["grife"];    
 	$situacao 		= $_GET["situacao"];    
  	
  	echo 'situacao: '.$situacao.'</br>';
    echo 'grife: '.$grife.'</br>';
    echo 'rep: '.$representantes;
    
    $where = ' where repres in ('.$representantes.') ';

   
   
    if (isset($_GET["situacao"])) {
    $situacao = $_GET["situacao"];
    

    $query1 = \DB::select("

select * from (
select * from (
    select *,  
    case when (meses <= 6.9 and meses_max = meses) then 1 else 0 end as c_novos,
      case when (meses <= 6.9 and meses_max = meses) and financeiro is not null then 1 else 0 end as c_novos_inad,
      
      case when (meses <= 6.9 and meses_max <> meses)  then 1 else 0 end as c_fid,
      case when (meses <= 6.9 and meses_max <> meses) and financeiro is not null then 1 else 0 end as c_fid_inad,
    case when (meses <= 6.9 and meses_max <> meses) then qtde else 0 end as q_fid,


    case when (meses > 6.9 and meses <= 12) then 1 else 0 end as c_n_fid,
    case when meses >= 6.9 and meses <= 12 and financeiro is not null then 1 else 0 end as c_n_fid_inad,
    case when meses > 6.9 and meses <= 12 then qtde else 0 end as q_n_fid,

    case when meses > 12 then 1 else 0 end as c_rec_fid,
    case when meses > 12 and financeiro is not null then 1 else 0 end as c_rec_inad,
    case when meses > 12 then qtde else 0 end as q_rec_fid


    from (


      select fim2.*, financeiro, tipo, uf, municipio from (
      
       select cliente, grife, diretor, supervisor, repres, min(meses) meses, max(meses) meses_max, sum(qtde) qtde from (
        select vds.cliente, trim(grife_jde) grife, abdir.fantasia diretor, absup.fantasia supervisor, abrep.fantasia repres,
        cast(datediff(cast(concat(year(now()),'-',month(now()),'-','01') as date), cast(concat(ano,'-',mes,'-',01) as date))/30  as decimal(12,0) ) meses,
        sum(qtde) qtde

        from vendas_cml vds 

		left join addressbook abrep on vds.repres = abrep.id
        left join addressbook absup on vds.supervisor = absup.id
        left join addressbook abdir on vds.diretor = abdir.id


              
        $where and ((vds.tipo = '12m' and ano <> '2018') or vds.tipo like '2%') and vds.cliente is not null
              -- and cliente like '100283 - CARVALHO  FERNANDES DE ITU LTDA EPP%'
		
        group by cliente, trim(grife_jde),  diretor, supervisor, repres,
        cast(datediff(cast(concat(year(now()),'-',month(now()),'-','01') as date), cast(concat(ano,'-',mes,'-',01) as date))/30  as decimal(12,0) )
       
            ) as fim1
           group by cliente, grife, diretor, supervisor, repres


      ) as fim2 
      
      left join (
      select distinct cliente cli, financeiro from addressbook where financeiro in ('in','ju') 
      ) ab 
      on ab.cli = fim2.cliente


      left join (
select cli1, tipo, GROUP_CONCAT(fantasia) uf, GROUP_CONCAT(municipio) municipio from (
	select cliente cli1, 'manter' as tipo ,  fantasia, municipio
	from addressbook 
	where grupo not in ('FUNCION?RIOS','REPRESENTANTES','TERCEIROS','QUIOSQUES - ATP','QUIOSQUE LUPA','ATLETAS','SPEEDO BRASIL','LOJA ANA HICKMANN') 
	group by cliente, fantasia, municipio
) as t1
group by cli1, tipo

     ) ab1 
      on ab1.cli1 = fim2.cliente



    ) as fim3
   ) as fim4
  where (tipo = 'manter' or tipo is null) 
   and  $situacao > 0  
   and grife = '$grife'
) as base


left join (
select cli, grife_jde, sum(a2019) a2019, sum(a2018) a2018, sum(a2017) a2017 from (
	select cliente cli, grife_jde,
	case when ano = '2019' then qtde else 0 end as a2019,
	case when ano = '2018' and tipo = '2018' then qtde else 0 end as a2018,
	case when ano = '2017' then qtde else 0 end as a2017
	from vendas_cml 
) as fim
group by cli, grife_jde
) as vendas
on vendas.cli = base.cliente and vendas.grife_jde = base.grife


    ");
  }

@endphp

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>Descrição</th>
              <th>Financeiro</th>
               <th>UF</th>
               <th>UF</th>
                <th>Responsavel</th>
              
               <th>a2019</th>
               <th>a2018</th>
               <th>a2017</th>
          </tr>
        </thead>
        <tbody> 

          @if ($query1)

            @foreach ($query1 as $linha)

              <tr>

				<td><a href="/clientes/fidelizados_cli?repres={{$linha->repres}}&cliente={{$linha->cliente}}"><i class="fa fa-unlock"></i></a></td> 
                <td>{{$linha->cliente}}</td>
                <td>{{$linha->financeiro}}</td> 
                <td>{{$linha->uf}}</td>
                <td>{{$linha->municipio}}</td>
                <td>{{$linha->repres}}</td>
                <td>{{$linha->a2019}}</td>
                <td>{{$linha->a2018}}</td>
                <td>{{$linha->a2017}}</td>

              </tr>


            @endforeach


          @endif
        </tbody>
      </table>
    </div>
</div>
@stop