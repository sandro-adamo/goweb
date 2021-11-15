@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhamento dos Pedidos @if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
@append 

@section('conteudo')

@php
    $representantes = Session::get('representantes');
	$ano 			= $_GET["ano"];    
 	$mes 			= $_GET["mes"];    
  	
  	echo 'ano: '.$ano.'</br>';
    echo 'mes: '.$mes.'</br>';
    echo 'rep: '.$representantes;
    
    $where = ' where rep_comissao in ('.$representantes.') ';

   
   
    if (isset($_GET["ano"])) {
    $situacao = $_GET["ano"];
    

    $query1 = \DB::select("
		select ano, mes, pedido, data_pedido, data_venda, num_mobile, codcli, razao, desc_status, cond_pag, tabela_desc, num_nf_legal, data_nf, 
    sum(qtde) qtde, sum(valor) valor, 
	sum(qtde_faturado) qtde_faturado, sum(vlr_faturado) vlr_faturado, sum(qtde_pedido) qtde_naofaturado, sum(vlr_pedido) vlr_naofaturado from (
			
            select pedido, data_pedido, data_venda, num_mobile, codcli, razao, desc_status, cond_pag, tabela_desc, num_nf_legal, case when data_nf = '0001-01-01' then '' else data_nf end as data_nf,  
            year(data_pedido) ano, month(data_pedido) mes,

				case when desc_status not in ('Erro Sefaz - aguardando correção','Pronto para contabilizar','NF rejeitada','Nota enviada para o Compliance','Entrega ao transportador','Nota Fiscal Autorizada','Minuta Impressa','Processo finalizado') then qtde else 0 end as qtde_pedido,
				case when desc_status not in ('Erro Sefaz - aguardando correção','Pronto para contabilizar','NF rejeitada','Nota enviada para o Compliance','Entrega ao transportador','Nota Fiscal Autorizada','Minuta Impressa','Processo finalizado') then valor else 0 end as vlr_pedido,
				case when desc_status in ('Erro Sefaz - aguardando correção','Pronto para contabilizar','NF rejeitada','Nota enviada para o Compliance','Entrega ao transportador','Nota Fiscal Autorizada','Minuta Impressa','Processo finalizado') then qtde else 0 end as qtde_faturado,
				case when desc_status in ('Erro Sefaz - aguardando correção','Pronto para contabilizar','NF rejeitada','Nota enviada para o Compliance','Entrega ao transportador','Nota Fiscal Autorizada','Minuta Impressa','Processo finalizado') then valor else 0 end as vlr_faturado,			
				qtde, valor

			from portal_pedidos
			left join addressbook ab on portal_pedidos.codcli = ab.id
            
            -- where rep_comissao = '93342'  and year(data_pedido) = '2019' and month(data_pedido) = '12' and desc_status not in ('Backorder', 'cancelado') 
			 $where and desc_status not in ('Backorder', 'cancelado') and year(data_pedido) = '$ano' and month(data_pedido) = '$mes' 
	) as fim 	
    group by ano, mes, pedido, data_pedido, data_venda, num_mobile, codcli, razao, desc_status, cond_pag, tabela_desc, num_nf_legal, data_nf	
    order by  data_nf desc
");
  }

@endphp
<h6>
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
      
     
        <div class="col-md-4">
        </div>
      </div>      
      <br>
     <table class="table table-bordered table-condensed compact" id="myTable">
        <thead>
          <tr>
          	<th>Pedido Gerado</th>
			<th>Data Pedido</th>
			<th>Data Venda</th>
			<th>Num Mobile</th>
			<th>Cod Cliente</th>
			<th>Razao Social</th>
			<th>Status Pedido</th>
			<th>Cond Pgto</th>
			<th>Tab desconto</th>
			<th>Vlr Faturado</th>
        	<th>Data NF</th>
			<th>Vlr Nao Fat</th>
			  
         
          </tr>
        </thead>
        <tbody> 

          @if ($query1)
        
			  @php
			  $total_ped = 0;
			  $total_fat = 0;
			  $total_nfat = 0;

			  @endphp

            @foreach ($query1 as $linha)
              
               @php
              $total_ped += $linha->valor;
			  $total_fat += $linha->vlr_faturado;
			  $total_nfat += $linha->vlr_naofaturado;


            @endphp

              <tr>
				<td align="center" class="text-red"><a href="/clientes/pedidos_ped?pedido={{$linha->pedido}}">{{$linha->pedido}}</a></td>
                <td>{{$linha->data_pedido}}</td>                
                <td>{{$linha->data_venda}}</td>                
                
                <td>{{$linha->num_mobile}}</td>
                <td>{{$linha->codcli}}</td>
                <td>{{$linha->razao}}</td>
                <td>{{$linha->desc_status}}</td>
          
                <td>{{$linha->cond_pag}}</td>
                <td>{{$linha->tabela_desc}}</td>
                <td>{{number_format($linha->vlr_faturado, 2, ',', '.')}}</td>
                <td>{{$linha->data_nf}}</td>
				<td>{{number_format($linha->vlr_naofaturado, 2, ',', '.')}}</td>
              </tr>


            @endforeach


          @endif
        </tbody>           
        <td><b>TOTAL</b></td>
         <td></td>
         <td></td>

         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td><b><div>{{number_format(@$total_ped, 2, ',', '.')}}</div></b></td>
		 <td><b><div>{{number_format(@$total_fat, 2, ',', '.')}}</div></b></td>
		 <td></td>
		 <td><b><div>{{number_format(@$total_nfat, 2, ',', '.')}}</div></b></td>
      </table>
      
   
 
 </div>
</div>
</h6>
@stop