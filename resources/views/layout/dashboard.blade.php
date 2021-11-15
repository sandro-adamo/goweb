@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard1
@append 

@section('conteudo')
dd(\Auth::user()->id );
@if  ( \Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil == 2 and \Auth::user()->id <> 461)
@php

$query = DB::select("
select sum(Linha_errada_rx) as 'Linha_errada_rx',
sum(Linha_errada_sl) as 'Linha_errada_sl', 
sum(Ajustar_armazenamento) as 'Ajustar_armazenamento', sum(Ajustar_preco) as 'Ajustar_preco', sum(Colocar_inativo) as 'Colocar_inativo', sum(Colecao_modelo) as 'Colecao_modelo', sum(Colecao_item) as 'Colecao_item', sum(Ano_modelo) as 'Ano_modelo', sum(Ano_item) as 'Ano_item', sum(modelo) as 'Modelo',
sum(EAN) as 'EAN', sum(Clas_mod) as 'Clas_mod', sum(Clas_item) as 'Clas_item', sum(fornecedor) as 'Fornecedor'

from(
select 
case when agrup like '%01%' and linha = 'receituario' and codtipoarmaz not in ('o', 'i')  then '1'
else '0' end as 'Linha_errada_rx',
case when agrup like '%02%' and linha = 'solar' and codtipoarmaz not in ('o', 'i') then '1'
else '0' end as 'Linha_errada_sl',
case when codstatusatual not in ('esgot','prod') and anomod > '2016'  and codtipoarmaz in ('o', 'i')then '1'
else '0' end as 'Ajustar_armazenamento',
case when codstatusatual not in ('esgot','prod','.','') and anomod > '2016' and codtipoarmaz not in ('o', 'i')  and valortabela < '30' then '1' else '0' end as 'Ajustar_preco',
case when codstatusatual = ('esgot')  and codtipoarmaz not in ('o', 'i') and anomod < '2016' then '1'
else '0' end as 'Colocar_inativo',
case when colmod in ('.','')   then '1'
else '0' end as 'Colecao_modelo',
case when colitem in ('.','')   then '1'
else '0' end as 'Colecao_item',
case when anomod in ('.','')   then '1'
else '0' end as 'Ano_modelo',
case when anoitem in ('.','')   then '1'
else '0' end as 'Ano_item',
case when modelo in ('.','')   then '1'
else '0' end as 'modelo',
case when ean in ('.','') and codstatusatual not in ('esgot','prod') and anomod >= '2017' and codtipoarmaz not in ('o', 'i')then '1'
else '0' end as 'EAN',
case when clasmod in ('.','')   then '1'
else '0' end as 'Clas_mod',
case when clasitem in ('.','')   then '1'
else '0' end as 'Clas_item',
case when fornecedor not in ('WENZHOU ZHONGMIN GLASSES CQ LTDA', 'TINYE OPTICAL GROUP COMPANY LIMITED', 'ALIALUX SRL', 'XIAMEN PROSPER OPTICAL TEC COM LTDA',
'MIRAGE S P A', 'WENZHOU DECO INTERNATONAL CO', 'WENZHOU READSUN OPTICAL CO LTD', '', 'CLAIR MONT IND DE COM LTDA', 'BENSOL', 'KENERSON IND E COMERCIO DE PROD OPTICOS',
'KERING EYEWEAR USA INC', 'WENZHOU LUJIA CO LYDA', 'KENERSON IND E COM DE PROD OPTICOS LTDA', 'WEIMEI INTERNATIONAL TRADINGHK LIMITED', 'KERING EYEWEAR SPA',
'kering')   then '1'
else '0' end as 'fornecedor'

from itens
where codtipoitem = '006'
and colmod not in ('2019 07', '2019 08', '2019 11')
and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
) as base
where concat(Linha_errada_rx, Linha_errada_sl,Ajustar_armazenamento, Ajustar_preco,Colocar_inativo,Colecao_modelo,Colecao_item,Ano_modelo,Ano_item,modelo,ean,clas_mod,Clas_item,fornecedor) <> '00000000000000'
");

@endphp



          <h3><b>Ajustar cadastro</b></h3> 
<h5><b>Filtros gerais </b></br>
					codtipoitem = '006'</br>
					+ colmod não contém ('2019 07', '2019 08', '2019 11')</br>
					+ codgrife contém ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')</h5> 

<div class="box-body no-padding">

		  <div class="box-header with-border">
              <table class="table table-striped">
			   <tr >
              <td align="left"><b>Tipo ajuste</b></td>
			<td align="left"><b>Quantidade para justar</b></td>  
			  <td align="left"><b>Obs</b></td>
				<td align="left"><b>Filtro</b></td>
			  
			  </tr>
            <tr >
             <td align="left">Linha RX</td>
			<td align="left" ><a href="/erroscadastro?ajuste=linha_errada_rx">{{ $query[0]->Linha_errada_rx }}</a></td>  
			  <td align="left">Verificar a linha dos itens receituarios que não estão em acordo agrupamento e linha.</td>
				<td align="left">and agrup like '%02%' </br>
					and linha = 'solar' </br>
					and codtipoarmaz not in ('o', 'i')</td>
			  	  </tr>

<tr >
             <td align="left">Linha SL</td>
			<td align="left" ><a href="/erroscadastro?ajuste=linha_errada_sl">{{ $query[0]->Linha_errada_sl }}</a></td>  
			  <td align="left">Verificar a linha dos itens solar que não estão em acordo agrupamento e linha.</td>
				<td align="left">and agrup like '%01%' </br>
					and linha = 'receituario' </br>
					and codtipoarmaz not in ('o', 'i')</td>
			  	  </tr>

 
             <tr > <td width="3%">Ajustar Armazenamento</td> 
				<td align="left"><a href="/erroscadastro?ajuste=ajustar_armazenamento">{{ $query[0]->Ajustar_armazenamento }}</a></td>
			   <td align="left">Ajustar armazenamento dos itens que não estão ativos.</td>
				<td align="left">codstatusatual not in ('esgot','prod','','.') </br>
				and anomod > '2016'  </br>
				and codtipoarmaz in ('o', 'i')	</td></tr>
			  
			  
             <tr > <td align="left">Ajustar Preço</td> 
				<td align="left"><a href="/erroscadastro?ajuste=ajustar_preco">{{ $query[0]->Ajustar_preco }}</a></td>
			   <td align="left">Peças disponiveis com preços errados.</td>
				<td align="left">codstatusatual not in ('esgot','prod','','.') </br>
				and anomod > '2016' </br>
				and codtipoarmaz not in ('o', 'i')  and valortabela < '30'</td></tr>
			  
			  
              <tr ><td align="left">Colocar Inativo</td> 
				<td align="left"><a href="/erroscadastro?ajuste=colocar_inativo">{{ $query[0]->Colocar_inativo }}</a></td>
			   <td align="left">Retirar de venda produtos descontinuados.</td>
				<td align="left">codstatusatual = ('esgot')  </br>
				and codtipoarmaz not in ('o', 'i') </br>
				and anomod < '2016'</td></tr>

			  
             <tr > <td align="left">Colecao Modelo</td> 
				 <td align="left"><a href="/erroscadastro?ajuste=colecao_modelo">{{ $query[0]->Colecao_modelo }}</a></td>
			   <td align="left">Coleção modelo vazia ou ponto.</td>
				<td align="left">colmod in ('.','') </td></tr>
			  
			  
              <tr ><td align="left">Colecao Item</td>
				<td align="left"><a href="/erroscadastro?ajuste=colecao_item">{{ $query[0]->Colecao_item }}</a></td>
			   <td align="left">Coleção item vazia ou ponto.</td>
				<td align="left">colitem in ('.','') </td></tr>
			  
			  
              <tr ><td align="left">Ano Modelo</td>
				<td align="left"><a href="/erroscadastro?ajuste=ano_modelo">{{ $query[0]->Ano_modelo }}</a></td>
			   <td align="left">Ano do modelo vazia ou ponto.</td>
				<td align="left">anomod in ('.','') </td></tr>
			  
			  
              <tr ><td walign="left">Ano Item</td>
				<td align="left"><a href="/erroscadastro?ajuste=ano_item">{{ $query[0]->Ano_item }}</a></td>
			   <td align="left">Ano item vazio ou ponto.</td>
				<td align="left">anoitem in ('.','')</td></tr>
			  
			  
              <tr ><td align="left">Modelo</td>
				<td align="left"><a href="/erroscadastro?ajuste=modelo">{{ $query[0]->Modelo }}</a></td>
			   <td align="left">Modelo vazio ou ponto.</td>
				<td align="left">modelo in ('.','')</td></tr>
			  
			  
              <tr ><td align="left">Ean</td> 
				<td align="left"><a href="/erroscadastro?ajuste=ean">{{ $query[0]->EAN }}</a></td>
			   <td align="left">Ean sem cadastro.</td>
				<td align="left">ean in ('.','') </br>
				and codstatusatual not in ('esgot','prod') </br>
				and anomod >= '2017'</br>
				and codtipoarmaz not in ('o', 'i')</td></tr>
			  
			  
             <tr > <td align="left">Clas Modelo</td>
				<td align="left"><a href="/erroscadastro?ajuste=clas_mod">{{ $query[0]->Clas_mod }}</a></td>
			   <td align="left">Classificação modelo vazia ou ponto.</td>
				<td align="left">clasmod in ('.','') </td></tr>
			  
             <tr > <td align="left">Clas Item</td>
				<td align="left"><a href="/erroscadastro?ajuste=clas_item">{{ $query[0]->Clas_item }}</a></td> 
			   <td align="left">CLassificação vazia ou ponto.</td>
				<td align="left">clasitem in ('.','') </td></tr>
			  
			  
             <tr > <td align="left">Fornecedor</td>
				<td align="left"><a href="/erroscadastro?ajuste=fornecedor">{{ $query[0]->Fornecedor }}  </a></td> 
			   <td align="left">Codigo do fornecedor errado.</td>
				<td align="left" fornecedor not in ('WENZHOU ZHONGMIN GLASSES CQ LTDA', 'TINYE OPTICAL GROUP COMPANY LIMITED', 'ALIALUX SRL', 'XIAMEN PROSPER OPTICAL TEC COM LTDA','MIRAGE S P A', 'WENZHOU DECO INTERNATONAL CO', 'WENZHOU READSUN OPTICAL CO LTD', '', 'CLAIR MONT IND DE COM LTDA', 'BENSOL', 'KENERSON IND E COMERCIO DE PROD OPTICOS','KERING EYEWEAR USA INC', 'WENZHOU LUJIA CO LYDA', 'KENERSON IND E COM DE PROD OPTICOS LTDA', 'WEIMEI INTERNATIONAL TRADINGHK LIMITED', 'KERING EYEWEAR SPA',
				'kering') </td></tr>
                             	
          




<!--
              <td><a href="">
             </a></td>
-->
              
              
              
              
              
             
              
              
              
              
              
              
               
              



            


 
</table>
</div>
</div>

</div>
				


@endif
@stop