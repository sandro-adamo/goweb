@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
<div class="row">

  @foreach ($marcas as $marca)
  <div class="col-md-3">
    <div class="box box-widget box-body" style="min-height: 150px;" align="center">
      <div style="height: 80px;">
        <img src="/img/marcas/{{$marca->grife}}.PNG" width="180px" style="max-height: 60px;" class="img-responsive">
      </div>
      <center><span class="label bg-blue">{{$marca->agrup}}</span></center>
		
			
		<a href="/exportasalesreport/"><span class="label bg-blue">Sales Report</span></a>
	  
		
		
      @foreach ($marcas->agrupamentos as $grup)
      <a href="/painel/"><span class="label bg-blue"></span></a>
		
		
     
      
      @endforeach

    </div>
  </div>
  @endforeach
  <center>{{ $marcas->links() }}</center>
</div>
@stop