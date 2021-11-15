@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Reports Gestão de Grifes
@append 

@section('conteudo')




@php
$sql1 = DB::select("  

select Agrupamento, sum(Atende_Disp) Atende_Disp, sum(Atende_TO) Atende_TO, sum(Atende_CET) Atende_CET, sum(Atende_ETQ) Atende_ETQ, sum(Atende_CEP) Atende_CEP, sum(Total_Atende) Total_Atende, sum(Orc_Pendente) Orc_Pendente



from(
select *, (Atende_Disp+Atende_TO+Atende_CET+Atende_ETQ+Atende_CEP) as Total_Atende, Orcamento-(Atende_Disp+Atende_TO+Atende_CET+Atende_ETQ+Atende_CEP) as Orc_Pendente

from(
select *,if((Orcamento-(Atende_Disp+Atende_TO+Atende_CET+Atende_ETQ))>0 , if(CEP-(Orcamento-(Atende_Disp+Atende_TO+Atende_CET+Atende_ETQ))>0 , (Orcamento-(Atende_Disp+Atende_TO+Atende_CET+Atende_ETQ)) , CEP) , 0) Atende_CEP

from(
select *,if((Orcamento-(Atende_Disp+Atende_TO+Atende_CET))>0 , if(ETQ-(Orcamento-(Atende_Disp+Atende_TO+Atende_CET))>0 , (Orcamento-(Atende_Disp+Atende_TO+Atende_CET)) , ETQ) , 0) Atende_ETQ

from(
select *,if((Orcamento-(Atende_Disp+Atende_TO))>0 , if(CET-(Orcamento-(Atende_Disp+Atende_TO))>0 , (Orcamento-(Atende_Disp+Atende_TO)) , CET) , 0) Atende_CET

from(
select*, if((Orcamento-Atende_Disp)>0 , if((Em_Beneficiamento+Saldo_Parte)-(Orcamento-Atende_Disp)>0 , (Orcamento-Atende_Disp), (Em_Beneficiamento+Saldo_Parte)) , 0) Atende_TO

from(
select itens.id Num_Curto, itens.grife Grife, itens.linha Linha, itens.agrup Agrupamento, itens.modelo Modelo, itens.secundario Cod_Secundario, itens.valortabela Valor, 
itens.anomod Ano_Modelo, itens.colmod Col_Modelo, itens.anoitem Ano_Item, itens.colitem Col_Item, itens.clasmod Class_Modelo, itens.clasitem Class_Item, 

if(saldos.disponivel<0,0,saldos.disponivel) Disponivel, case when (if(saldos.disponivel<0,0,saldos.disponivel)-ifnull(orcamentos.orctt,0))<=0 then 0 else (if(saldos.disponivel<0,0,saldos.disponivel)-ifnull(orcamentos.orctt,0)) end as Disponivel_Real, saldos.existente Existente, saldos.res_definitiva, saldos.res_temporaria, ifnull(orcamentos.orctt,0) Orcamento, 
saldos.conf_montado Conf_Montado, saldos.em_beneficiamento Em_Beneficiamento, saldos.saldo_parte Saldo_Parte, saldos.cet CET, ifnull(producoes_sint.estoque,0) ETQ, ifnull(producoes_sint.producao,0) CEP, saldos.saldo_most Mostruario, saldos.saldo_trocas Reserva_Trocas, saldos.saldo_manutencao Manutencao, 

ifnull(sum(vendas_sint.ult_30dd),0) V_30D, ifnull(sum(vendas_sint.ult_60dd),0) V_60D, ifnull(sum(vendas_sint.ult_90dd),0) V_90D, ifnull(sum(vendas_sint.ult_120dd),0) V_120D, ifnull(sum(vendas_sint.ult_150dd),0) V_150D, 
ifnull(sum(vendas_sint.ult_180dd),0) V_180D, ifnull(sum(vendas_sint.ult_210dd),0) V_210D, ifnull(sum(vendas_sint.ult_240dd),0) V_240D, ifnull(sum(vendas_sint.ult_270dd),0) V_270D, ifnull(sum(vendas_sint.ult_300dd),0) V_300D, 
ifnull(sum(vendas_sint.ult_330dd),0) V_330D, ifnull(sum(vendas_sint.ult_360dd),0) V_360D, ifnull(sum(vendas_sint.vendastt),0) Venda_Total,

itens.tipoitem Tipo_Item, itens.tipoarmaz Tipo_Armaz, itens.fornecedor Fornecedor, 
itens.ultstatus Ult_Status, itens.statusatual Status_Atual, 

if(orcamentos.orctt>0,if((if(saldos.disponivel<0,0,saldos.disponivel)+Conf_Montado)-orcamentos.orctt>0,orcamentos.orctt,(if(saldos.disponivel<0,0,saldos.disponivel)+Conf_Montado)),0) Atende_Disp

from go.itens 
left join go.saldos on itens.id = saldos.curto
left join go.vendas_sint on itens.id = vendas_sint.curto
left join go.orcamentos on itens.id = orcamentos.curto
left join 
(select sum(estoque) estoque, sum(producao) producao, cod_sec
from go.producoes_sint
group by cod_sec) as producoes_sint  on itens.secundario = producoes_sint.cod_sec

where
orcamentos.orctt>0 and
itens.tipoarmaz not like 'obsoleto%' and itens.tipoitem not like 'mpdv%' and itens.tipoitem not like 'agregados%' and itens.tipoitem not like 'frente%' and
itens.agrup not like 'ff0%' and itens.agrup not like 'gf0%' and itens.agrup not like 'jr0%' and itens.agrup not like 'mb0%' and itens.agrup not like 'mo0%' and itens.agrup not like 'ms0%' and itens.agrup not like 'or0%' and itens.agrup not like 'pp0%' and itens.agrup not like 'sy0%'

group by itens.id, itens.grife, itens.linha , itens.agrup , itens.modelo, itens.secundario , itens.primario , itens.ean , itens.valortabela , 
itens.anomod , itens.colmod , itens.anoitem , itens.colitem , itens.clasmod , itens.clasitem , 

saldos.disponivel ,  saldos.existente , saldos.res_definitiva, saldos.res_temporaria, orcamentos.orctt, 
saldos.conf_montado,  saldos.em_beneficiamento , saldos.saldo_parte , saldos.cet , producoes_sint.estoque, producoes_sint.producao, saldos.saldo_most , saldos.saldo_trocas , saldos.saldo_manutencao , 

itens.tipoitem , itens.tipoarmaz , itens.fornecedor , 
itens.ultstatus, itens.statusatual , itens.material , itens.genero , itens.idade , itens.estilo , itens.fixacao , itens.tecnologia , itens.tamolho , itens.tamponte , itens.tamhaste, 
itens.ultcusto, itens.mediacusto , itens.descricao 
order by itens.agrup, itens.modelo, itens.secundario asc) as xx) as xx1) as xx2) as xx3) as xx4) as xx5 group by Agrupamento



");


//dd($sql1);
	
@endphp
	
	
	<head>   
  
		
	   <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

         google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Agrupamento');
		data.addColumn('number', 'Atende_Disp');
		data.addColumn('number', 'Atende_TO');
		data.addColumn('number', 'Atende_CET');
		data.addColumn('number', 'Atende_ETQ');
		data.addColumn('number', 'Atende_CEP');
		data.addColumn('number', 'Total_Atende');
		data.addColumn('number', 'Orc_Pendente');
        
        data.addRows([
		@foreach ($sql1 as $dados1) 

            @php 	
            $agrupamento = $dados1->Agrupamento;
            $Atende_Disp = $dados1->Atende_Disp;
			$Atende_TO = $dados1->Atende_TO;
			$Atende_CET = $dados1->Atende_CET;
			$Atende_ETQ = $dados1->Atende_ETQ;
			$Atende_CEP = $dados1->Atende_CEP;
			$Total_Atende = $dados1->Total_Atende;
			$Orc_Pendente = $dados1->Orc_Pendente;

			
			@endphp
			
			
          ['{{$agrupamento}}',  
		  {v: {{$Atende_Disp}}},
		  {v: {{$Atende_TO}}},
		  {v: {{$Atende_CET}}},
		  {v: {{$Atende_ETQ}}},
		  {v: {{$Atende_CEP}}},
		  {v: {{$Total_Atende}}},
		  {v: {{$Orc_Pendente}}}],
		  
		  
		  @endforeach
          
        ]);

        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
      }
    </script>
 
       
	
		<div id="table_div" style="width: 1000px; height: 1000px;"></div>         
		
	  
</head>

@stop