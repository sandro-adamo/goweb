@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

<?php

 
$agrup = $_GET["agrup"];

//echo 'agrup'.' - '.$agrup;

$query = DB::select(
	"select agrup, 
sum(mes1) as mes1,
sum(mes2) as mes2,
sum(mes3) as mes3,
sum(mes4) as mes4,
sum(mes5) as mes5,
sum(mes6) as mes6,
sum(mes7) as mes7,
sum(mes8) as mes8,
sum(mes9) as mes9,
sum(mes10) as mes10,
sum(mes11) as mes11,
sum(mes12) as mes12,
sum(total) as total, 
sum(mes1)/sum(total) as p1,
sum(mes2)/sum(total) as p2,
sum(mes3)/sum(total) as p3,
sum(mes4)/sum(total) as p4,
sum(mes5)/sum(total) as p5,
sum(mes6)/sum(total) as p6,
sum(mes7)/sum(total) as p7,
sum(mes8)/sum(total) as p8,
sum(mes9)/sum(total) as p9,
sum(mes10)/sum(total) as p10,
sum(mes11)/sum(total) as p11,
sum(mes12)/sum(total) as p12

from(

select agrup, 
case when mes = 1 then meta else 0 end as mes1,
case when mes = 2 then meta else 0 end as mes2,
case when mes = 3 then meta else 0 end as mes3,
case when mes = 4 then meta else 0 end as mes4,
case when mes = 5 then meta else 0 end as mes5,
case when mes = 6 then meta else 0 end as mes6,
case when mes = 7 then meta else 0 end as mes7,
case when mes = 8 then meta else 0 end as mes8,
case when mes = 9 then meta else 0 end as mes9,
case when mes = 10 then meta else 0 end as mes10,
case when mes = 11 then meta else 0 end as mes11,
case when mes = 12 then meta else 0 end as mes12,
case when 0 = 0 then sum(meta) else 0 end as total

from metas 
where agrup = '$agrup'

group by agrup, mes, meta

) as sele1
group by agrup
");

?>

<div class="col-md-12" >         
          <div class="col-md-12" align="center">   
           <table border="2">
              <tr width="70%"><b>Sazonalidade Mensal - <?php echo $agrup; ?> </b></tr>     
               
               <tr align="center">
               	<td width="3%">Total</td> 
                <td width="3%">P1</td> 
                <td width="3%">P2</td>
                <td width="3%">P3</td>
                <td width="3%">P4</td>  
                <td width="3%">P5</td>  
                <td width="3%">P6</td>   
                <td width="3%">P7</td> 
                <td width="3%">P8</td> 
                <td width="3%">P9</td> 
                <td width="3%">P10</td> 
                <td width="3%">P11</td> 
                <td width="3%">P12</td>        	
               </tr>
           
 <?php      foreach ($query as $dados) { 
//    print_r($user);
//    echo $dados->item.'</br>'; 
?>

	 <tr> 
<td align="center"><small><?php echo number_format($dados->total,0) ?></small></td>
<td align="center"><small><?php echo number_format(($dados->p1*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p2*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p3*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p4*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p5*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p6*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p7*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p8*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p9*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p10*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p11*100),2).'%'?> </small></td>
<td align="center"><small><?php echo number_format(($dados->p12*100),2).'%'?> </small></td>
	
     </tr>	   
 <?php }
?>
		</table>		
	 </div>  	


  <div class="col-md-12" align="center">   
           <table border="2">
              <tr width="70%"><b>Meta Vendas Mensal - <?php echo $agrup; ?> </b></tr>     
               
               <tr align="center">
               	<td width="3%">Total</td> 
                <td width="3%">P1</td> 
                <td width="3%">P2</td>
                <td width="3%">P3</td>
                <td width="3%">P4</td>  
                <td width="3%">P5</td>  
                <td width="3%">P6</td>   
                <td width="3%">P7</td> 
                <td width="3%">P8</td> 
                <td width="3%">P9</td> 
                <td width="3%">P10</td> 
                <td width="3%">P11</td> 
                <td width="3%">P12</td>        	
               </tr>
           
 <?php      foreach ($query as $dados) { 
//    print_r($user);
//    echo $dados->item.'</br>'; 
?>

	 <tr> 
<td align="center"><small><?php echo number_format($dados->total,0)?></small></td>
<td align="center"><small><?php echo number_format($dados->mes1,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes2,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes3,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes4,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes5,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes6,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes7,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes8,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes9,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes10,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes11,0)?> </small></td>
<td align="center"><small><?php echo number_format($dados->mes12,0)?> </small></td>
	
     </tr>	   
 <?php }
?>
		</table>		
	</div>






	</div>


@stop