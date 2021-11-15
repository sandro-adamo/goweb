@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Reports Gestão de Grifes
@append 

@section('conteudo')




@php
$sql = DB::select("
select Agrupamento, count(Cod_secundario) as Secundario, sum(Reorder_Total) as Reorder, sum(Reorder_6m) as Reorder6m

from(select Fornecedor, Num_Curto, Agrupamento, Modelo, Cod_Secundario, Status_Atual, Valor, Col_Modelo, Col_Item, date_format(data_morte,'%Y %m') as Col_Morte, Class_Modelo, Class_Item, Orcamento, Estoque_Total, mdv,
if( timestampdiff(month,curdate(),data_morte)<0,0,timestampdiff(month,curdate(),data_morte) ) as Meses_Vida,
case when timestampdiff(month,curdate(),data_morte)>2.9 then 
if( (mdv*( if( timestampdiff(month,curdate(),data_morte)>6,6, timestampdiff(month,curdate(),data_morte) )*0.7))-(Estoque_Total-Orcamento)<0,0,ceil((mdv*(if( timestampdiff(month,curdate(),data_morte)>6,6, timestampdiff(month,curdate(),data_morte) )*0.7))-(Estoque_Total-Orcamento)) ) else 0 end as Reorder_6m,

case when data_morte>date_add(curdate(),interval 45 day) then 
if( (mdv*(timestampdiff(month,curdate(),data_morte)*0.7))-(Estoque_Total-Orcamento)<0,0,ceil((mdv*(timestampdiff(month,curdate(),data_morte)*0.7))-(Estoque_Total-Orcamento)) ) else 0 end as Reorder_Total
,Description

from(
select Fornecedor, Num_Curto, Agrupamento, Modelo, Cod_Secundario, Status_Atual, Valor, Col_Modelo,
case when Class_Modelo like 'LINHA A++' then if(linha like 'solar',date_add(data_mod,interval 480 day), date_add(data_mod,interval 720 day))
when Class_Modelo like 'LINHA A+' then if(linha like 'solar',date_add(data_mod,interval 360 day), date_add(data_mod,interval 540 day)) 
when Class_Modelo like 'LINHA A-' then if(linha like 'solar',date_add(data_mod,interval 120 day), date_add(data_mod,interval 180 day)) 
when Class_Modelo like 'COLEÇÃO B' then if(linha like 'solar',date_add(data_mod,interval 120 day), date_add(data_mod,interval 180 day)) 
when Class_Modelo like 'PROMOCIONAL C' then if(linha like 'solar',date_add(data_mod,interval 120 day), date_add(data_mod,interval 180 day)) else
if(linha like 'solar',date_add(data_mod,interval 240 day), date_add(data_mod,interval 360 day)) end as data_morte, 
Col_Item, Class_Modelo, Class_Item, Orcamento, (Disponivel+Conf_Montado+Em_Beneficiamento+Saldo_Parte+CET+ETQ+CEP) as Estoque_Total, mdv, Description

from(
select itens.id Num_Curto, itens.linha, itens.agrup Agrupamento, itens.modelo Modelo, itens.secundario Cod_Secundario, itens.valortabela Valor, 
itens.colmod Col_Modelo, itens.colitem Col_Item, CONCAT(SUBSTR(colmod,1,4),'-',SUBSTR(colmod,6,2),'-01') as data_mod, itens.clasmod Class_Modelo, itens.clasitem Class_Item, 

if(saldos.disponivel<0,0,saldos.disponivel) Disponivel, ifnull(orcamentos.orcvalido,0) Orcamento, 
saldos.conf_montado Conf_Montado, saldos.em_beneficiamento Em_Beneficiamento, saldos.saldo_parte Saldo_Parte, saldos.cet CET, ifnull(producoes_sint.estoque,0) ETQ, ifnull(producoes_sint.producao,0) CEP,

ifnull(sum(vendas_sint.ult_30dd),0) V_30D, ifnull(sum(vendas_sint.ult_60dd),0) V_60D, ifnull(sum(vendas_sint.ult_90dd),0) V_90D, ifnull(sum(vendas_sint.vendastt),0) Venda_Total,

(select (media1+media2+media3)/3 

from(

select*,
  ifnull(( select venda   from _mediaitem where _mediaitem.secundario = base2.secundario and venda<> media2 and venda<> media1 limit 1 ),0) as media3
  from(

	select *,
	  ifnull(( select venda 
	  from _mediaitem where _mediaitem.secundario = base1.secundario and venda<> media1 limit 1 ),0) as media2
	  
	from(

		select itens.agrup as agrupamento, itens.modelo, itens.secundario, itens.clasmod, itens.statusatual,
		ifnull(( select venda 
		  from _mediaitem where _mediaitem.secundario = itens.secundario limit 1 ),0) as media1

		from go.itens
		where itens.tipoarmaz not like 'obsoleto%' and itens.tipoitem not like 'mpdv%' and itens.tipoitem not like 'agregados%' and itens.tipoitem not like 'frente%'
) as base1) as base2) as base3 where base3.secundario = itens.secundario) as mdv,

itens.fornecedor Fornecedor, itens.statusatual Status_Atual, itens.descricao Description


from go.itens 
left join go.saldos on itens.id = saldos.curto
left join go.vendas_sint on itens.id = vendas_sint.curto
left join go.orcamentos on itens.id = orcamentos.curto
left join 
(select sum(estoque) estoque, sum(producao) producao, cod_sec
from go.producoes_sint
group by cod_sec) as producoes_sint  on itens.secundario = producoes_sint.cod_sec

where
itens.tipoarmaz not like 'obsoleto%' and itens.tipoitem not like 'mpdv%' and itens.tipoitem not like 'agregados%' and itens.tipoitem not like 'frente%' and
itens.agrup not like 'ff0%' and itens.agrup not like 'gf0%' and itens.agrup not like 'jr0%' and itens.agrup not like 'mb0%' and itens.agrup not like 'mo0%' and itens.agrup not like 'ms0%' and itens.agrup not like 'or0%' and itens.agrup not like 'pp0%' and itens.agrup not like 'sy0%'

group by itens.id, itens.linha, itens.agrup , itens.modelo, itens.secundario , itens.valortabela , 
itens.colmod , itens.colitem , itens.clasmod , itens.clasitem , 
saldos.disponivel ,  orcamentos.orcvalido, 
saldos.conf_montado,  saldos.em_beneficiamento , saldos.saldo_parte , saldos.cet , producoes_sint.estoque, producoes_sint.producao,
itens.fornecedor , itens.statusatual , itens.descricao 

order by itens.agrup, itens.modelo, itens.secundario asc
)as base_reorder

where Class_Modelo not like 'promocional c' and Class_modelo not like 'coleção b') as info


) as base
where (fornecedor like 'kering%' and Reorder_6m >0) or (Reorder_6m >100)
group by Agrupamento
");

//dd($sql);

@endphp


  
        


<div class="row" >       
    <div class="col-md-12">
      <div class="box box-widget">

        <div class="box-header with-border">
          <h3 class="box-title">
          
          </h3> 
        </div>         

        <div class="box-body">

          <h5><b>Repedido</b></h5>     

          <table class="box-body table-responsive table-striped">
            <tr align="center">
              
              <td width="7%">Agrupamento</td>
             
              <td width="3%">Qtd Secundario</td>
				<td width="3%">Total 6 meses</td>
				<td width="3%">Total Reorder</td>
				
                           	
            </tr>
			  
			  


           


         

              @foreach ($sql as $dados) 

            @php 	
            $agrupamento = $dados->Agrupamento;
			
			$reorder = $dados->Reorder;
			$secundario = $dados->Secundario;
			  $reorder6m = $dados->Reorder6m;
        


			
			@endphp
			     <tr>
		<td><small><a href="/repedidodetalhe?agrupamento={{$agrupamento}}">
              {{$agrupamento}}</a></small></td>
                           
              
              <td align="center"><small>{{ $secundario}}</small></td>
					 <td align="center"><small>{{ $reorder6m}}</small></td>
				<td align="center"><small>{{ $reorder}}</small></td>
		
             </tr>
	@endforeach 

              
              
          </table>

        </div>	


      </div>
    </div>
  </div>
      

@stop