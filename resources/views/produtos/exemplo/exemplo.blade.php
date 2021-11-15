@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

<?php

 
$modelo = $_GET["modelo"];

echo 'modelo'.' - '.$modelo;

$query = DB::select("
select secundario, sum(mala) most 
from mostruarios most
left join itens on itens.id = most.cod_curto
where modelo = '$modelo'
group by secundario
");


$query1 = DB::select("select secundario, sum(mala) most 
from mostruarios most
left join itens on itens.id = most.cod_curto
where modelo = '$modelo'
group by secundario");


$query2 = DB::select("
select  * from producoes_hist 
where modelo_go = '$modelo' 
order by dt_relatorio");

?>
			
 			<div class="col-md-12" >
          <div class="col-md-12" align="center">
           <table>   
       
               <tr>
               	<td width="20%">secundario</td>
               	<td width="10%">mala</td>
           
               </tr>
             
 <?php      foreach ($query as $dados) {  ?>           
                <tr>
                  <td><small><?php echo $dados->secundario?></small></td>
                  <td><small><?php echo $dados->most?></small></td>
                  
                </tr>
				
					 
 <?php }
?>
			</table>
			
		</div>	
	</div>
	
	
			
				
			
 			<div class="col-md-12" >
          <div class="col-md-5" align="center">
           <table>     
               <tr>
               	<td width="20%">modeloGO</td>
               	
               </tr>
             
 <?php      foreach ($query1 as $dados1) {  ?>

           
                <tr>
                  <td><small><?php echo $dados1->secundario?></small></td>
                   <td><small><?php echo $dados1->most?></small></td>
                </tr>
				
					 
 <?php }
?>
			</table>
			
		</div>	
	</div>
	
	
	
	 			<div class="col-md-12" >
          <div class="col-md-12" align="center">
           <table>   
       
               <tr>
               	<td width="20%">dt_relatorio</td>
               	<td width="20%">cod_sec</td>
               	<td width="10%">qtd_pedido</td>
               	<td width="10%">qtd_enviada</td>
               	<td width="10%">saldo</td>
               	<td width="10%">producao</td>
               	<td width="20%">dt_entrega</td>
           
               </tr>
             
 <?php      foreach ($query2 as $dados) {  ?>           
                <tr>
                  <td><small><?php echo $dados->dt_relatorio?></small></td>
                  <td><small><?php echo $dados->cod_sec?></small></td>
                  <td><small><?php echo $dados->qtd_pedido?></small></td>
                   <td><small><?php echo $dados->qtd_enviada?></small></td>
                    <td><small><?php echo $dados->saldo?></small></td>
                     <td><small><?php echo $dados->producao?></small></td>
                     <td><small><?php echo $dados->dt_entrega?></small></td>
                  
                </tr>
				
					 
 <?php }
?>
			</table>
			
		</div>	
	</div>
	
@stop