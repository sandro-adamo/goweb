@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

@php
 	
  $cli = $_GET["cliente"];
  echo $cli;
  

  $representantes = Session::get('representantes');
  echo $representantes;

@endphp


<?php



//echo 'agrup'.' - '.$agrup;

$query = DB::select("
select * from (
select * from (
select  
case 
when subgrupo not in ('','.') then subgrupo
when grupo not in ('','.') then grupo
else concat(id,' - ',razao) end as cliente,
id codcliente, cnpj, razao, fantasia, uf, municipio, endereco, bairro, grupo, subgrupo
from addressbook
) as sele    
where cliente = '$cli' 
) as sele1


left join (
	select cod_cli, sum(vencido) as vencido, sum(em_aberto) em_aberto from (
	select case 
    when subgrupo not in ('','.') then subgrupo
    when grupo not in ('','.') then grupo
    else concat(cliente.id,' - ',razao) end as cliente1,
    cod_cli, 
    
	case when status_pgto = 'em aberto' then sum(valor_em_aberto) else 0 end as vencido,
	case when status_pgto <> 'em aberto' then sum(valor_em_aberto) else 0 end as em_aberto
	from a_receber
    left join addressbook cliente on cod_cli = cliente.id 
	
    group by cod_cli, status_pgto 
    ) as sele1
    group by cod_cli
) as receber
on receber.cod_cli  = sele1.codcliente 

");

?>

<div class="col-md-12" >         
          <div class="col-md-12" align="center">   
           <table border="2">
              <tr width="70%"><b>Sazonalidade Mensal - <?php echo '' ?> </b></tr>     
               <tr align="center">
               	<td width="5%">codcli</td> 
                <td width="5%">cnpj</td> 
                <td width="15%">razao</td> 
                <td width="10%">fantasia</td> 
                <td width="2%">uf</td> 
                <td width="5%">municipio</td> 
                <td width="15%">endereco</td> 
                <td width="5%">bairro</td> 
                <td width="5%">grupo</td> 
                <td width="5%">subgrupo</td> 
                <td width="5%">vencido</td> 
                <td width="5%">a vencer</td> 
                                 	
               </tr>
                          
 <?php      
			   foreach ($query as $dados) { 
//    print_r($user);
//    echo $dados->item.'</br>'; 
?>

	 <tr> 
<td align="left"><a href=""><small><?php echo $dados->codcliente ?></small></a></td>
<td align="left"><a href=""><small><?php echo $dados->cnpj ?></small></a></td>
<td align="left"><a href=""><small><?php echo $dados->razao ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->fantasia ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->uf ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->municipio ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->endereco ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->bairro ?></small></a></td>
    <td align="left"><a href=""><small><?php echo $dados->grupo ?></small></a></td>
     <td align="left"><a href=""><small><?php echo $dados->subgrupo ?></small></a></td>
     <td align="right"><a href=""><small><?php echo number_format($dados->vencido, 2, ',', '.') ?></small></a></td>
     <td align="right"><a href=""><small><?php echo number_format($dados->em_aberto, 2, ',', '.') ?></small></a></td>
     </tr>	   
 <?php }
?>
		</table>		
	</div>  	
</div>


@stop