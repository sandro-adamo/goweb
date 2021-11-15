@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

<?php

 
$agrup		 	= $_GET['agrup'];
$colecao 		= $_GET['colmod'];



echo 'agrup'.' - '.$agrup.'<br>';
echo 'colecao'.' - '.$colecao.'<br>';

$query = DB::select("select distinct ciclos.modelo, ciclos.colecao, agrup, itens.colmod,

(select secundario from itens b where itens.modelo = b.modelo order by secundario limit 1) as secundario,
(select sum(vendastt) from vendas_sint vd where vd.modelo = ciclos.modelo) as vendas,

(select sum(disp_vendas+conf_montado+em_beneficiamento+saldo_parte+qtd_rot_receb+saldo_manutencao+saldo_most+saldo_trocas) as tt
from saldos
left join itens on itens.secundario = saldos.secundario
where itens.modelo = ciclos.modelo) as saldos,

(select sum(saldo_most) as tt
from saldos
left join itens on itens.secundario = saldos.secundario
where itens.modelo = ciclos.modelo) as most


from ciclos 
left join itens on itens.modelo = ciclos.modelo
where agrup = '$agrup' and colecao = '$colecao' order by ciclos.modelo");

?>

           
<div class="row">

  <?php      foreach ($query as $catalogo) { 			   ?>
 
    <div class="col-md-2" > 
      <div class="box box-body box-widget" style="height: 220px;"> 
       <td><b><i class="text-default">{{$catalogo->modelo}}</i></b></td>
       <td><i class="pull-right">{{$catalogo->colmod}}</i></td>
        <div style="max-height: 160px; height: 160px; min-height: 160px;">
          <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">
          <img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$catalogo->secundario}}" class="img-responsive">
          </a>
        </div>  
		<table class="table-condensed">
		<tr>
			<td><i class="fa fa-shopping-cart text-green">{{number_format($catalogo->vendas,0)}}</i></td>
			<td><i class="fa fa-cubes text-orange">{{number_format($catalogo->saldos,0)}}</i></td>
			<td><i class="fa fa-suitcase text-navy-blue">{{number_format($catalogo->most,0)}}</i></td>
			<td><i class="fa fa-suitcase text-red">{{number_format($catalogo->most,0)}}</i></td>
		</tr>
		</table>       
      </div>
     </div>
  
 <?php }
?>
</div>			
					 




@stop