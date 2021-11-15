@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

<?php

 
$agrup = $_GET["agrup"];

//echo 'agrup'.' - '.$agrup;

$query = DB::select(
	"
	select*
	from(
	select id, agrup, genero, idade, material, clas_mod, clas_item, sug_compra, mdv_mensal, chave, timestamp,
	case when clas_mod = 'linha a++' then 1
	when clas_mod = 'linha a+' then 2
	when clas_mod = 'linha a' then 3
	else 3 end as ordermod,
	case when clas_item = 'linha a++' then 1
	when clas_item = 'linha a+' then 2
	when clas_item = 'linha a' then 3
	else 3 end as orderitem
	from sugestoes where agrup = '$agrup' and idade = '') as base
	order by ordermod asc, orderitem asc,genero asc, idade, material, clas_mod, clas_item  
");

?>

<div class="row" >         
  <div class="col-md-12">  
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Sales & Order Sugestion - {{ $agrup }} </h3>
      </div>
      <div class="box-body">  
       <table class="table table-bordered table-condensed">
        <tr width="70%"><b></b></tr>     

        <tr align="center">
         
          <td width="3%">Clas_mod</td>
          <td width="3%">Clas_item</td>  
          <td width="3%">Avg Month</td>  
          <td width="3%">Order Sugestion</td>          	
        </tr>

        @foreach ($query as $dados)
        <tr> 
         
         <td align="center"><small><?php echo $dados->clas_mod?> </small></td>
         <td align="center"><small><?php echo $dados->clas_item?> </small></td>
         <td align="center"><small><?php echo $dados->mdv_mensal?> </small></td>
         <td align="center"><small><?php echo $dados->sug_compra?> </small></td>
       </tr>	   
       @endforeach
     </table>		
   </div>
 </div>
</div>  					


@stop