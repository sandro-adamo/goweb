	@extends('produtos/painel/index')

<title>Histórico estoque </title>
@section('title')
<i class="fa fa-list"></i> Novo
@append 

@section('conteudo')
@include('produtos.painel.modal.caracteristica')	
@php

$secundario = $_GET["secundario"];


	  
	  $query2 = DB::select("select date(data)as Data_Processa,secundario, existente, pre_pedido, em_separacao, saldo_partes, beneficiamento, cet, etq, cep, manutencao, orcamento, 
case when ultimo_st = 'disponível em 30 DIAS' or ultimo_st = 'AGUARDAR IMPORTACAO 30 DIAS' or ultimo_st = 'AGUARDAR IMPORTA??O 30 DIAS' then '30 dias'
when ultimo_st = 'disponível em 15 DIAS' or ultimo_st = 'AGUARDAR IMPORTACAO 15 DIAS'  or ultimo_st = 'AGUARDAR IMPORTA??O 15 DIAS' then '15 dias'
when ultimo_st = 'disponível' or ultimo_st = 'DISPON?VEL' or ultimo_st = 'entrega imediata' or ultimo_st = 'DISPONÃVEL' then 'Disponível'
when (ultimo_st = 'AGUARDAR PRODUÇÃO' or ultimo_st = 'em produção' or ultimo_st = 'AGUARDAR PRODUÃ‡ÃƒO' or ultimo_st = 'PRODUCAO' or ultimo_st = 'AGUARDAR PRODU??O') then 'Produção'
when ultimo_st = 'esgotado' then 'Esgotado'
else ultimo_st end as 'ultimo_st',

case when status_atual = 'disponível em 30 DIAS' or status_atual = 'AGUARDAR IMPORTACAO 30 DIAS' or status_atual = 'AGUARDAR IMPORTA??O 30 DIAS' then '30 dias'
when status_atual = 'disponível em 15 DIAS' or status_atual = 'AGUARDAR IMPORTACAO 15 DIAS'  or status_atual = 'AGUARDAR IMPORTA??O 15 DIAS' then '15 dias'
when status_atual = 'disponível' or status_atual = 'DISPON?VEL' or status_atual = 'entrega imediata'  or status_atual = 'DISPONÃVEL' then 'Disponível'
when (status_atual = 'AGUARDAR PRODUÇÃO' or status_atual = 'em produção' or status_atual = 'AGUARDAR PRODUÃ‡ÃƒO' or status_atual = 'PRODUCAO' or status_atual = 'AGUARDAR PRODU??O') then 'Produção'
when status_atual = 'esgotado' then 'Esgotado'
else status_atual end as 'status_atual',

case when status3 = 'disponível em 30 DIAS' or status3 = 'AGUARDAR IMPORTACAO 30 DIAS' or status3 = 'AGUARDAR IMPORTA??O 30 DIAS' then '30 dias'
when status3 = 'disponível em 15 DIAS' or status3 = 'AGUARDAR IMPORTACAO 15 DIAS'  or status3 = 'AGUARDAR IMPORTA??O 15 DIAS' then '15 dias'
when status3 = 'disponível' or status3 = 'DISPON?VEL' or status3 = 'entrega imediata' or status3 = 'DISPONÃVEL' then 'Disponível'
when (status3 = 'AGUARDAR PRODUÇÃO' or status3 = 'em produção' or status3 = 'AGUARDAR PRODUÃ‡ÃƒO' or status3 = 'PRODUCAO' or status3 = 'AGUARDAR PRODU??O') then 'Produção'
when status3 = 'esgotado' then 'Esgotado'
else status3 end as 'status3',



 armazenamento
	  from processa
      where secundario = '$secundario'
	  order by data desc
	  
	 

");



@endphp
	  <div class="box-body">

          <h5><b>{{$query2[0]->secundario}}</b></h5>     
<a href="" class="zoom" data-value="{{$query2[0]->secundario}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$query2[0]->secundario}}" style="max-height: 100px;" class="img-responsive"></a>
          <table class="box-body table-responsive table-striped">
            <tr align="center">
              
				<td width="5%" align="center"><b>Data</b>
			  <td width="3%"><b>Existente</b></td>
              
			  <td width="3%"><b>Benefi</b></td>
              <td width="3%"><b>Partes</b></td>
              
              <td width="3%"><b>Cet</b></td>
              <td width="3%"><b>Etq</b></td>
              <td width="3%"><b>Cep</b></td>
              <td width="3%"><b>Manut</b></td>
			  <td width="3%"><b>Pre-pedido</b></td>
              <td width="3%"><b>Separação</b></td>
              <td width="3%"><b>Orç</b></td>
              <td width="5%"><b>Ultimo Status</b></td>               	
              <td width="5%"><b>Status Atual</b></td>               	
              <td width="5%"><b>Próximo Status</b></td> 
              <td width="2%"><b>Armaz</b></td>               	
                             	
            </tr>
	  
	   @foreach ($query2 as $dados) 
	  
	  <tr align="center">
              
              <td width="3%" align="center">{{$dados->Data_Processa}}</td>
              <td width="3%">{{number_format($dados->existente,0)}}</td>
			 <td width="3%">{{number_format($dados->beneficiamento,0)}}</td>
              <td width="3%">{{number_format($dados->saldo_partes,0)}}</td>
              
              <td width="3%">{{number_format($dados->cet,0)}}</td>
              <td width="3%">{{number_format($dados->etq,0)}}</td>
              <td width="3%">{{number_format($dados->cep,0)}}</td>
              <td width="3%">{{number_format($dados->manutencao,0)}}</td>
		      <td width="3%">{{number_format($dados->pre_pedido,0)}}</td>
              <td width="3%">{{number_format($dados->em_separacao,0)}}</td>
              <td width="3%">{{number_format($dados->orcamento,0)}}</td>
              <td width="3%">{{$dados->ultimo_st}}</td>               	
              <td width="4%">{{$dados->status_atual}}</td>               	
              <td width="4%">{{$dados->status3}}</td> 
              <td width="2%">{{$dados->armazenamento}}</td>               	
                             	
            </tr>
	  @endforeach
</table>

        </div>	

  @stop