@extends('produtos/painel/index')

@section('titulo', 'Painel de Produtos') 

@section('title')
<i class="fa fa-object-group"></i> Products Panel
@append 

@section('conteudo') 

<div class="row">
  @if (count($agrupamentos) > 0)
  @foreach ($agrupamentos as $agrupamento)
  <div class="col-md-3">
    <div class="box box-widget box-body" style="min-height: 220px; height: 220px" align="center">
      <div style="height: 80px;">
        <img src="/img/marcas/{{$agrupamento->grife}}.png" width="180px" style="max-height: 60px;" class="img-responsive">
      </div>


       @if ( \Auth::user()->admin == 1  
        or  \Auth::user()->id_perfil == 2 )
      <tr>
       <a href="/sugestoes?agrup={{$agrupamento->agrup}}">
        <i class="fa fa-object-group" title="Sales Sugestion">
        Sug</a></i>
      </tr>

      <a href="/sugestoes2?agrup={{$agrupamento->agrup}}">
        <i  class="fa fa-object-group" title="Sales Sugestion">
        Sug2</a></i>
      </tr>

      <tr>
       <a href="/sazonalidades?agrup={{$agrupamento->agrup}}">
        <i class="fa fa-object-group" title="sazonalidade">
        Sazo</a></i>
      </tr>
	  
	   <tr>
       <a href="/rep_grife?grife={{$agrupamento->codgrife}}">
        <i class="fa fa-users" title="sazonalidade">
        representantes</a></i>
      </tr>
	  
	  	   <tr>
       <a href="/timeline_grife?grife={{$agrupamento->agrup}}">
        <i class="fa fa-suitcase" title="sazonalidade">
        grade</a></i>
      </tr>
      
      <tr>
       
	   <tr>
       <a href="/rep_grife?grife={{$agrupamento->codgrife}}">
        <i class="fa fa-users" title="sazonalidade">
        representantes</a></i>
      </tr>
	  
	  	   <tr>
       <a href="/timeline_grife?grife={{$agrupamento->agrup}}">
        <i class="fa fa-users" title="sazonalidade">
        timeline</a></i>
      </tr>
      @endif
	  
	  
	  
	  
      <center><a href="/painel/{{$agrupamento->agrup}}"><span class="label bg-green">{{$agrupamento->agrup}}</span></a></center>
      @if (isset($colecoes) and count($colecoes) > 0 )
        @foreach ($colecoes as $ano)

     
	   <a href="/painel/<?=urldecode($agrupamento->agrup)?>/?anomod={{$ano->anomod}}"><span class="label bg-blue">{{$ano->anomod}}</span></a>
		
		
          @endforeach
        </br>
        <a  href="/exportaprecosugerido/<?=urldecode($agrupamento->agrup)?>" ><B> Sugerido</B> <i  class="fa fa-archive "></i>Excel</a>
		<a  href="/exportaprecosugeridod/<?=urldecode($agrupamento->agrup)?>" ><i  class="fa fa-archive "></i>Excel disp</a>

       <!--  <a  href="/fotos/PRECO_SUGERIDO/<?=urldecode($agrupamento->agrup)?>.jpg" download="<?=urldecode($agrupamento->agrup)?>" ><i  class="fa fa-picture-o "></i>JPG</a> -->
        @endif
        @if ( \Auth::user()->admin == 1  or  \Auth::user()->id_perfil == 11 
        or  \Auth::user()->id_perfil == 2 )

        <br/>

        <tr>
         <a href="/topmodelos?agrupamento={{$agrupamento->agrup}}">
          <i class="fa fa-object-group" title="topmodelos">
          Top modelos</a></i>
        </tr>


        <a href="/exportasalesreport/<?=urldecode($agrupamento->agrup)?>" ><i class="fa fa-paste "></i>Sales Report</a>

        @endif
      </div>
    </div>
    @endforeach
    @endif
  </div>
  @stop