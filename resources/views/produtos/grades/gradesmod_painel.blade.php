
<div class="row">

 
  <div class="col-md-12">
    <span class="lead">Modelos</span>
    <div class="row">

@php
		
$gradesmod = \DB::select(" 
		
	select fornecedor, grife, codgrife, agrup, modelo, colecao, 
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
					and codtipoarmaz not in ('o') and agrup = 'AH02 - ANA HICKMANN (RX)'
				) as fim2
			) as fim3 group by fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao
		) as fim4 
	) as fim5 group by fornecedor, grife, codgrife, agrup, colecao, modelo
) as fim6 where novos > 0 
group by fornecedor, grife, codgrife, agrup, modelo, colecao
order by fornecedor, agrup

");
		
	
		
        @endphp

		
@foreach ($gradesmod as $catalogo)
		
		
      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->modelo}}</span>
			  <span class="pull-right">{{$catalogo->colecao}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			  @if ($catalogo->itens > 0 )
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->itens}} </b>
				</td>
          </tr> </table>
            @endif
              
				

		
		@if ($catalogo->imediata > 0 and  $catalogo->imediata < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->imediata >= 1 and  $catalogo->futura >= 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		
		@endif
		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

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
			  
			  
			  

<div> 
@php
$mesesforn = 2;
   
@endphp     

     <div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-shopping-cart text-green"></i></td>
                            
                            <td>
                              @if ( \Auth::user()->admin == 1  or  \Auth::user()->id_perfil == 11 
								or  \Auth::user()->id_perfil == 2 )
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->d0cor)}}/{{number_format($catalogo->d0cor)}}</a>
                              @else 
                                {{number_format($catalogo->d0cor)}}
                              @endif 
                            </td>
                            
                        </tr>
                    </table>

                </td>

				
				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{number_format($catalogo->imediata)}}</td>
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
      </div>
      @endforeach

     

    </div>
  </div>

</div>


