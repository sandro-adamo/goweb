@extends('layout.principal')

@section('title')
<i class="fa fa-calendar"></i> Programação entrega
@append 

@section('conteudo')

<div class="col-md-12" >
    <div class="box box-widget" >
      <div class="box-header with-border"  > 
      @foreach($entregas as $entrega)
      
        <div class="col-lg-4" style="min-height:600px">
          <div class="box box-primary">
            <h3> {{$entrega->dt_completa}}</h3>
            <table class="table table-bordered table-condensed">
              
              <tr> 
               <th> Confirmado </th>
               <th> Previsão </th>
      <th> Qtd embarque </th>
             </tr>
               <tr>
                  
                   <td>{{number_format($entrega->confirmado)}}</td>
                    <td>{{number_format($entrega->previsao)}} </td>
            <td>{{number_format($entrega->tt_pedido)}} </td>
              </tr>
             
              </table>
        
       
           </div>
        @php

        $agrup = \DB::select("

     Select sum(qtentrega) as qtd, dt_completa, agrup
    from(
select *,
    case 
    when month(dtentrega) = 1 then 'a'
    when month(dtentrega) = 2 then 'b'
    when month(dtentrega) = 3 then 'c'
    when month(dtentrega) = 4 then 'd'
    when month(dtentrega) = 5 then 'e'
    when month(dtentrega) = 6 then 'f'
    when month(dtentrega) = 7 then 'g'
    when month(dtentrega) = 8 then 'h'
    when month(dtentrega) = 9 then 'i'
    when month(dtentrega) = 10 then 'j'
    when month(dtentrega) = 11 then 'k'
    when month(dtentrega) = 12 then 'l'
    else 'Z' end as ordem,
    concat(year(dtentrega),'-',month(dtentrega)) as dt_completa,
        year(dtentrega) anoentrega
        from(
    select agrup, compras_entregas.dt_confirmada dtconfirmada, fornecedor, compras.obs, item, ifnull(qtde_confirmada,0) as qtd_confirmada, ifnull(qtde,0) as qtde_pedida, compras.id,  ifnull(compras_entregas.qtde_entrega,0) as qtd_entrega,
		concat(year(compras_entregas.dt_confirmada),'-',month(compras_entregas.dt_confirmada)) as dt,year(compras_entregas.dt_confirmada) as ano,
       compras_entregas.dt_confirmada,  case 
      
      when compras_entregas.dt_confirmada is null and compras.dt_entrega  <= current_date then concat(year(current_date),'-',month(current_date),'-28')
       when compras_entregas.dt_confirmada is null then compras.dt_entrega
        when compras_entregas.dt_confirmada is not null and compras_entregas.dt_confirmada <= current_date then concat(year(current_date),'-',month(current_date),'-28')
       else compras_entregas.dt_confirmada end as dtentrega
       , case when compras_entregas.dt_confirmada is null then 'previsao' else 'confirmado' end as tipo,
       case when compras_entregas.dt_entrega is null then ifnull(qtde,0) else ifnull(compras_entregas.qtde_entrega,0)-ifnull(compras_entregas.qtd_entregue,0) end as qtentrega
        from compras
		left join compras_itens on compras_itens.id_compra = compras.id
		left join compras_entregas on compras_itens.id = compras_entregas.id_compra_item
		left join itens on itens.secundario = compras_itens.item
		where compras.dt_emissao >= 2020-06-01
		and fornecedor not like '%kering%'
		and fornecedor not like '%ZHONGMIN%'
		and compras_itens.status not in ('cancelado','FINALIZADO SISTEMA','concluido')
		and compras_itens.status in ('distribuido', 'aguardando documentacao')
		and ifnull(compras_entregas.qtde_entrega,0)-ifnull(compras_entregas.qtd_entregue,0) > 0
		and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
		and ifnull(compras_entregas.qtde_entrega,0) <> 0
		 and (compras_entregas.dt_alterada is null or compras_entregas.dt_alterada =0)
		 and fornecedor not like '%kenerson%'
		 and itens.codtipoitem = 006) as base) as base2
        where dt_completa = '$entrega->dt_completa'
        group by dt_completa, agrup
    
    ");

        @endphp
      @foreach($agrup as $agrups)
      </br>
      <b>{{$agrups->agrup}}</b> - 
      {{number_format($agrups->qtd)}}
    
      @endforeach
        </div>
		    
      @endforeach
		   </div>
</div>
	</div> 
        
   
    
 

@stop