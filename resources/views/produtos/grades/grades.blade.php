@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')


@php


$gradeslista = \DB::select(" 
select fornecedor, grife, codgrife, agrup, count(modelo) modelos,
	sum(novos) novos, sum(aa) aa, sum(a) a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
from (

	select fornecedor, grife, codgrife, agrup, colecao, modelo,
	case when colecao = 'novo' then 1 else 0 end as novos,
	case when colecao = 'aa' then 1 else 0 end as aa, 
	case when colecao = 'a' then 1 else 0 end as a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
	from (

		select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
			case when imediata+futura >= 3 then 1 else 0 end as am3cores,
			case when imediata+futura  = 2 then 1 else 0 end as b2cores,
			case when imediata+futura  = 1 then 1 else 0 end as c1cor,
			case when imediata+futura  = 0 then 1 else 0 end as d0cor,
			
			 case when colecao = 'novo' then 'novo'
			 when colecao <> 'novo' and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') then 'aa'
			 when colecao <> 'novo' and clasmod in ('LINHA A-') then 'a' else '' end as colecao
			
			
		from(

			select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado
			
			from (
			 
				select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, 1 as itens,
					case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
					case when ultstatus like '%DIAS' then 1 else 0 end as futura,
					case when ultstatus like '%PROD%' then 1 else 0 end as producao,
					case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
				from (
							
					select case when fornecedor like 'kering%' then 'kering' else 'go' end as fornecedor,
					grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus,
					case when (left(colmod,4) <= year(now()) and right(colmod,2) < month(now())) then 'lancado' else 'novo' end as colecao
					from itens 
					where itens.secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') and codtipoitem = 006				 
					and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
					 and codtipoarmaz not in ('o')
				) as fim2
			) as fim3 group by fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao
		) as fim4 
	) as fim5 group by fornecedor, grife, codgrife, agrup, colecao, modelo
) as fim6 group by fornecedor, grife, codgrife, agrup
order by fornecedor, agrup

");
			  
			
@endphp



<div class="col-md-12">
 <span class="lead">Grade de Modelos </span>
<div class="row">
 
 @foreach ($gradeslista as $catalogo)

  <div class="col-xs-3">
    <div class="box box-widget">
		
      <div class="box-header with-border" style="font-size:10px; padding: 3px 5px 3px 5px; margin-bottom: 0; vertical-align: top;">
        <span class="text-bold">{{trim($catalogo->agrup)}}</span> 
      </div>
          
      <div id="foto" align="center" style="margin-top:20px; min-height:140;height:140; top:20%; margin-bottom:0; padding-bottom:0;">
  
		<a href="" class="zoom" data-value="{{$catalogo->modelos}}"></a>     

        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto(trim($catalogo->codgrife));
        @endphp

        <a href="/produtos/gradescolecoes/{{$catalogo->agrup}}">
          <img src="/img/marcas/{{$catalogo->grife}}.png" style="max-height: 250px;" class="img-responsive">
        </a>     
      
      </div>

		
		
		
      <div class="box-body">

        <div class="row">
          <div class="col-sm-4 col-md-4">
            
          </div>
          
        </div>
        @php
    
      $mesesforn = 2;
   
@endphp     
       


  <div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: left;">                
						<tr>
							<td>Mod</i></td>  
                            <td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}">{{number_format($catalogo->modelos)}}</a></td> 
                        </tr>
                    </table>

                </td>
	  
	  
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td>N</i></td>
                            <td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}?cores=3">{{number_format($catalogo->novos)}}</a></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							<td>A</td>
							<td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}?cores=2">{{number_format($catalogo->aa)}}</a></td>
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td>A-</td>
                            <td><a href="/produtos/gradesmod_painel?agrup={$catalogo->agrup}}?cores=1">{{number_format($catalogo->a)}}</a></td>
                        </tr>
                    </table>
                </td>
	  
	  		
	  
            </tr>
        </table>
    </div>
</div>

	
	
	
	
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>               
	  
	  
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-battery-full text-green"></i></td>
                            <td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}?cores=3">{{number_format($catalogo->am3cores)}}</a></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							<td><i class="fa fa-battery-half text-blue"></i></td>
							<td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}?cores=2">{{number_format($catalogo->b2cores)}}</a></td>
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-battery-quarter text-yellow"></i></td>
                            <td><a href="/produtos/gradesmod_painel?agrup={{$catalogo->agrup}}?cores=1">{{number_format($catalogo->c1cor)}}</a></td>
                        </tr>
                    </table>
                </td>
	  
	  		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-battery-empty text-red"></i></td>
                            <td><a href="/produtos/gradesmod_painel/{{$catalogo->agrup}}?cores=0">{{number_format($catalogo->d0cor)}}</a></td>
                        </tr>
                    </table>
                </td>
	  
            </tr>
        </table>
    </div>
</div>	





 <div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="left">Itens</i></td>
							
								<td>{{number_format($catalogo->itens)}}</td>
							
						</tr>
                    </table>

                </td>
				
	 			<td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($catalogo->imediata)}}</td>
                        </tr>
                    </table>

                </td>

                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->futura)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->producao)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->esgotado)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
	</div>
	</div>
	</div>

	</div>
	</div> 

@endforeach 
   </div>
  </div>

</div>

@stop