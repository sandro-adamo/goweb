@extends('produtos/painel/index')

@section('titulo') {{$_GET["item"]}} - Vendas @append
@section('title')
<i class="fa fa-list"></i> Novo
@append 

@section('conteudo')
@include('produtos.painel.modal.caracteristica')	
@php

$item = $_GET["item"];


$query2 = DB::select("
select *
from itens
where secundario = '$item'
");
$query = DB::select("
select nome, dir, sup,an8,
ifnull((select malas.qtde from malas where ltrim(rtrim(malas.local)) = 'MALA' and malas.id_rep = an8 and malas.id_item = fim.id limit 1),0) as most,
colmod,
genero,
idade,
tamolho,
agrup as agrupamento,
modelo,
clasmod,
id iditem,
sum(l030)'a30',
sum(k3060)'b60',
sum(j6090)'c90',
sum(i90120) 'd120',
sum(h120150)'e150',
sum(g150180) 'f180',
sum(f180210) 'g210',
sum(e210240) 'h240',
sum(d240270)'i270',
sum(c270300) 'j300',
sum(b300330) 'k330',
sum(a330360) as 'l360', 
sum(g150180+h120150+i90120+j6090+k3060+l030) tt180,
sum(a330360+b300330+c270300+d240270+e210240+f180210+g150180+h120150+i90120+j6090+k3060+l030) tt360,
regiao
from (

select dt_emissao, va.qtde,
case when nome = '' then razao else nome end as nome,
ab.id as an8,
colmod,
genero,
idade,
tamolho,
itens.agrup,
itens.modelo,
clasmod,
itens.id,


 dir,
 sup,
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 4 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 4 month)) 
then sum(qtde) else 0 end as 'h120150',
case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 5 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 5 month)) 
then sum(qtde) else 0 end as 'g150180',
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 6 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 6 month)) 
then sum(qtde) else 0 end as 'f180210',

 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 7 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 7 month)) 
then sum(qtde) else 0 end as 'e210240',
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 8 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 8 month)) 
then sum(qtde) else 0 end as 'd240270',
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 9 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 9 month)) 
then sum(qtde) else 0 end as 'c270300',
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 10 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 10 month))
then sum(qtde) else 0 end as 'b300330',
 case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 11 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 11 month))
then sum(qtde) else 0 end as 'a330360',









case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 3 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 3 month)) 
then sum(qtde) else 0 end as 'i90120',



case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 2 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 2 month)) 
then sum(qtde) else 0 end as 'j6090',
case when month(dt_emissao) = month(date_sub(current_timestamp(), interval 1 month)) and  year(dt_emissao) = year(date_sub(current_timestamp(), interval 1 month)) 
then sum(qtde) else 0 end as 'k3060',
case when month(dt_emissao) = month(current_timestamp()) and  year(dt_emissao) = year(current_timestamp()) 
then sum(qtde) else 0 end as 'l030',
month(current_timestamp()), regiao

from vendas_jde va
left join itens on itens.id = id_item
left join addressbook ab on ab.id = id_rep
left join (
select sup, dir, rep, case when regiao ='' then uf else regiao end as regiao
from(
select ad.uf,
case when adsup.nome <> '' then adsup.nome else adsup.razao end as sup, 
case when addir.nome <> '' then addir.nome else addir.razao end as dir, 
 ad.id as rep, (select group_concat(distinct REGIAO, ' ') as regiao from carteira where dt_fim > date(now()) and regiao <> '' and ad.id = carteira.rep limit 1) as regiao from addressbook ad
left join addressbook adsup on    adsup.id = ad.id_supervisor
left join addressbook addir on    addir.id = ad.id_diretor

where ad.tipo = 're'
) as carteira
group by dir, sup, rep, regiao, uf
) as carteira on carteira.rep = va.id_rep 

where secundario = '$item'

and dt_emissao >=  cast(concat(year(date_sub(current_timestamp(), interval 12 month)),'-',month(date_sub(current_timestamp(), interval 12 month)),'-','01') as date) 
/*and format(concat(year(dt_emissao),month(dt_emissao)),0) >= format(concat(year(date_sub(current_timestamp(), interval 12 month)),month(date_sub(current_timestamp(), interval 12 month))),0)
*/
group by nome, dt_emissao, dir, sup, razao, dt_emissao, qtde, ab.id, colmod,
genero,
idade,
tamolho,
itens.agrup,
itens.modelo,
clasmod,
itens.id, regiao


) as fim

where nome IS NOT NULL

group by nome, dir, sup, an8, colmod,
genero,
idade,
tamolho,
agrup,
modelo,
clasmod,
id, regiao

order by sum(a330360+b300330+c270300+d240270+e210240+f180210+g150180+h120150+i90120+j6090+k3060+l030) desc

");



@endphp



 
  <div class="row" >       
    <div class="col-md-12">
      <div class="box box-widget">

        <div class="box-header with-border">
			 <div class="col-md-6">
          <h3 class="box-title">
			  Vendas</br>
			  Item - <a href="/painel/{{$query2[0]->agrup}}/{{$query2[0]->modelo}}"><?php echo $item.' / ' ;?></a>
          
			  
            @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11 or \auth::user()->id_perfil ==25) 
            <a href="" class="text-black alteraClasMod">{{$query2[0]->clasmod}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="classmod" data-value="{{$query2[0]->id}}"><i class="fa fa-edit"></i></a>@endif</a>
            @endif
        
          
          <?php echo '/'.$query2[0]->colmod.' / '; ?>
          <?php echo $query2[0]->genero.' / '; ?>
          <?php echo $query2[0]->idade.' / '; ?>
          <?php echo $query2[0]->tamolho; ?>
          </h3> 
			</div>
			 <div class="col-md-6">
			<a href="" class="zoom" data-value="{{$item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item}}" style="max-height: 100px;" class="img-responsive"></a>
			</div>
        </div> 
		

        <div class="box-body">

         
          <div class="table-responsive">
          <table class="table table-condensed table-bordered" id="myTable">
            <thead>
            <tr align="center">
              <td >Foto</td>
              <td >Rep</td>
				 <td >Most</td>
			  <td >Sup</td>
				<td >Dir</td>
				<td>Região</td>
              <td  align="center">30dd</td>
              <td  align="center">60dd</td>
              <td  align="center">90dd</td>
              <td  align="center">120dd</td>
              <td  align="center">150dd</td>
              <td  align="center">180dd</td>
              <td  align="center">210dd</td>
              <td  align="center">240dd</td>
              <td  align="center">270dd</td>
              <td  align="center">300dd</td>
<!--
              <td  align="center">330dd</td>
              <td  align="center">360dd</td>               	
-->
              <td  align="center">tt 180dd</td>               	
              <td  align="center"><b>tt 360</b></td> 
                          	
            </tr>
			  
			  
          </thead>
          <tbody>

            @php  
            $totala = 0;
            $totalb = 0;
            $totalc = 0;
            $totald = 0;
            $totale = 0;
            $totalf = 0;
            $totalg = 0;
            $totalh = 0;
            $totali = 0;
            $totalj = 0;
            $totall = 0;
            $totalm = 0;
            $total180 = 0;
            $total360 = 0;
            
            
            @endphp



            @foreach ($query as $dados) 

            @php 	
            $totala += $dados->a30;
            $totalb += $dados->b60;
            $totalc += $dados->c90;
            $totald += $dados->d120;
            $totale += $dados->e150;
            $totalf += $dados->f180;
            $totalg += $dados->g210;
            $totalh += $dados->h240;
            $totali += $dados->i270;
            $totalj += $dados->j300;
            $totall += $dados->k330;
            $totalm += $dados->l360;
            $total180 += $dados->tt180;
            $total360 += $dados->tt360;
            @endphp



            <tr>

              <td id="foto" align="center" style="min-height:20px;">
               
                <a href="" class="zoom" data-value="{{$dados->an8}}"><img src="https://portal-gestao.goeyewear.com.br/fotos/REPRESENTANTES/{{$dados->an8}}.JPG" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>

              <td><small>
              <?php echo $dados->nome?></small></td>
				<td><small>
					<?php echo $dados->most?></small></td>
				<td><small>
              <?php echo $dados->sup?></small></td>
				<td><small>
              <?php echo $dados->dir?></small></td>
				<td><small>
              <?php echo $dados->regiao?></small></td>
                           
              <td align="center"><small>{{ number_format($dados->a30,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->b60,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->c90,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->d120,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->e150,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->f180,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->g210,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->h240,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->i270,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->j300,0) }}</small></td>
<!--
              <td align="center"><small>{{ number_format($dados->k330,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->l360,0) }}</small></td>  
-->
              <td align="center"><small>{{ number_format($dados->tt180,0) }}  </small></td> 
              <td align="center"><small><b>{{ number_format($dados->tt360,0) }}</b> </small></td>

              
              

            </tr>

            @endforeach
          </small>
          <tfoot>
            <tr style="text-align: center; font-weight: bold;">
              <td></td>
				 <td></td>
				<td></td>
				<td></td>
              <td width="7%"><b>TOTAL</b></td>
              <td width="3%" align="center">{{$totala}}</td>
              <td width="3%" align="center">{{$totalb}}</td>
              <td width="3%" align="center">{{$totalc}}</td>
              <td width="3%" align="center">{{$totald}}</td>
              <td width="3%" align="center">{{$totale}}</td>
              <td width="3%" align="center">{{$totalf}}</td>
              <td width="3%" align="center">{{$totalg}}</td>
              <td width="3%" align="center">{{$totalh}}</td>
              <td width="3%" align="center">{{$totali}}</td>
              <td width="3%" align="center">{{$totalj}}</td>
<!--
              <td width="3%" align="center">{{$totall}}</td>
              <td width="3%" align="center">{{$totalm}}</td>
-->
              <td width="3%" align="center">{{$total180}}</td>
              <td width="3%" align="center">{{$total360}}</td>
			 

              
            </tr>
          </tfoot>
          </table>
        </div>
        </div>	


      </div>
    </div>
  </div>

  @stop












































