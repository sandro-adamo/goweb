@extends('layout.principal')

@section('title')
<i class="fa fa-file-o"></i> Modelo AT1240


@section('conteudo')

@if (Session::has('alert-success'))
  <div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 


<div class="row" >	
  <div class="col-md-12" >

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tripadvisor"></i> Modelo {{$item[0]->modelo_go}}</h3>
        <span class="pull-right"></span>
      </div>
      <div class="box-body"> 

 <form action="/compras/pedido/item/edita/salva" id="frmEditaCor" class="form-horizontal" method="post" 	enctype="multipart/form-data">
  @csrf

       
          <h4 class="title" >Edita Cor GO {{$item[0]->cod_cor}} / Fornecedor {{$item[0]->cod_cor_fornecedor}}</h4>
       
        <div class="body">
          <input type="hidden" name="id_item" value="{{$item[0]->id}}">
			<input type="hidden" name="id_modelo" value="{{$item[0]->id_modelo}}">
          <div class="form-group">
            <div class="col-md-12">
				<div class="col-md-6">
					<h3 align="left">Fábrica</h3>
					<table >
			<tr><th>Cod cor Fabrica</th></tr>
              <tr><td><input type="text" value="{{$item[0]->cod_cor_fornecedor}}" name="codcorfabrica" class="form-control">
              </input></td></tr>
						
              <tr><th>Cod cor GO</th></tr>
              <tr><td><input type="text" value="{{$item[0]->cod_cor_fornecedor}}" name="codcorgo" class="form-control">
              </input></td></tr>
			<tr><th>Classificação item</th></tr>
              @php 
              $classmod = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'clasmod'");
              @endphp
              <tr><td><select class="form-control" name="clasitem" >
               <option value="{{$item[0]->clasitem}}">{{$item[0]->clasitem}} </option>
			 @foreach ($classmod as $classmods)
			<option value="{{$classmods->valor}}">{{Strtoupper($classmods->valor)}}</option>
                 @endforeach
 			</select></td></tr>
			<tr><th>Cor frente 1</th></tr>
              <tr><td><textarea id="corfrente1" value="{{$item[0]->cor_frente1}}" name="corfrente1" rows="2" cols="30" class="form-control">{{$item[0]->cor_frente1}}
				</textarea></td></tr>
             <tr><th> Cor frente 2</th></tr>
              <tr><td> <textarea id="corfrente2" value="{{$item[0]->cor_frente2}}"name="corfrente2" rows="2" cols="30" class="form-control">{{$item[0]->cor_frente2}}
				</textarea></td></tr>
              <tr><th>Cor frente 3</th></tr>
              <tr><td><tr><td> <textarea id="corfrente3" name="corfrente3" value="{{$item[0]->cor_frente3}}" rows="2" cols="30" class="form-control">{{$item[0]->cor_frente3}}
				</textarea></td></tr>
             <tr><th>Cor haste 1</th></tr>
              <tr><td><textarea id="corhaste1" name="corhaste1" value="{{$item[0]->cor_haste1}}" rows="2" cols="30" class="form-control">{{$item[0]->cor_haste1}}
				</textarea></td></tr>
             <tr><th>Cor haste 2</th></tr>
              <tr><td><textarea id="corhaste2" name="corhaste2"  value="{{$item[0]->cor_haste2}}" rows="2" cols="30" class="form-control">{{$item[0]->cor_haste2}}
				</textarea></td></tr>
              <tr><th>Cor haste 3</th></tr>
              <tr><td><textarea id="corhaste3" name="corhaste3" rows="2" value="{{$item[0]->cor_haste3}}" cols="30" class="form-control">{{$item[0]->cor_haste3}}
				</textarea></tr></td>
             <tr><th>Ponteira</th></tr>
              <tr><td><textarea id="corponteira" name="corponteira" value="{{$item[0]->cor_ponteira}}" rows="2" cols="30" class="form-control">{{$item[0]->cor_ponteira}}
				</textarea></td></tr>
             <tr><th>Logo</th></tr>
              <tr><td> <textarea id="corlogo" name="corlogo" rows="2" cols="30" value="{{$item[0]->cor_logo}}" class="form-control">{{$item[0]->cor_logo}}
				</textarea></td></tr>
              <tr><th>Lente</th></tr>
              <tr><td> <textarea id="corlente" name="corlente" rows="2" cols="30" value="{{$item[0]->cor_lente}}" class="form-control">{{$item[0]->cor_lente}}
				</textarea></td></tr>
             <tr><th>Quantidade</th></tr>
              <tr><td><input type="number" name="quantidade" value="{{$item[0]->quantidade}}" class="form-control">
              </input></td></tr>
              <tr><th>Custo</th></tr>
              <tr><td><input type="decimal" name="custo" value="{{$item[0]->custo}}" class="form-control">
              </input></td></tr>
			</table>
            </div>
			 
	  <div class-"col-md-6">
		  <h3 align="left">JDE</h3>
		  <table  >
			<tr><th>Cod cor Fabrica</th></tr>
              <tr><td><input type="text" name="" disabled class="form-control">
              </input></td></tr>
						
              <tr><th>Cod cor GO</th></tr>
              <tr><td><input type="text" name="" disabled class="form-control">
              </input></td></tr>
			<tr><th>Classificação item</th></tr>
             
              <tr><td><select class="form-control" name="" disabled >
                <option value=""> </option>
			 
 			</select></td></tr>
			<tr><th>Cor frente 1</th></tr>
              <tr><td>@php 
              $corarm1 = \DB::select("select codigo, valor from caracteristicas where campo = 'corarm1' order by valor asc");
              @endphp
             <select class="form-control" name="corarm1jde" >
                <option value="{{$item[0]->cor_frente1_jde}}">{{$item[0]->cor_frente1_jde_valor}} </option>
                @foreach ($corarm1 as $corarm1s)
                <option value="{{$corarm1s->codigo}}">{{$corarm1s->valor}}</option>
                @endforeach
             </select></td></tr>
             <tr><th><br> Cor frente 2</th></tr>
              <tr><td>@php 
              $corarm2 = \DB::select("select codigo, valor from caracteristicas where campo = 'corarm2' order by valor asc");
              @endphp
             <select class="form-control" name="corarm2jde" >
                <option value="{{$item[0]->cor_frente2_jde}}">{{$item[0]->cor_frente2_jde_valor}} </option>
                @foreach ($corarm2 as $corarm2s)
                <option value="{{$corarm2s->codigo}}">{{$corarm2s->valor}}</option>
                @endforeach
             </select></td></tr>
              <tr><th><br>Cor frente 3</th></tr>
             <tr><td><textarea id="corfrente3" name="" disabled rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th>Cor haste 1</th></tr>
              <tr><td>@php 
              $corhaste1 = \DB::select("select codigo, valor from caracteristicas where campo = 'corhaste1' order by valor asc");
              @endphp
             <select class="form-control" name="corhaste1jde" >
                <option value="{{$item[0]->cor_haste1_jde}}">{{$item[0]->cor_haste1_jde_valor}} </option>
                @foreach ($corhaste1 as $corhaste1s)
                <option value="{{$corhaste1s->codigo}}">{{$corhaste1s->valor}}</option>
                @endforeach
             </select></td></tr>
             <tr><th><br>Cor haste 2</th></tr>
              <tr><td>@php 
              $corhaste2 = \DB::select("select codigo, valor from caracteristicas where campo = 'corhaste2' order by valor asc");
              @endphp
             <select class="form-control" name="corhaste2jde" >
                <option value="{{$item[0]->cor_haste2_jde}}">{{$item[0]->cor_haste2_jde_valor}} </option>
                @foreach ($corhaste2 as $corhaste2s)
                <option value="{{$corhaste2s->codigo}}">{{$corhaste2s->valor}}</option>
                @endforeach
             </select></td></tr>
              <tr><th><br>Cor haste 3</th></tr>
              <tr><td><textarea id="corhaste3" name="" disabled rows="2" cols="30" class="form-control">
				</textarea><strong></tr></td></strong>
             <tr><th>Ponteira</th></tr>
              <tr><td>@php 
              $corponteira = \DB::select("select codigo, valor from caracteristicas where campo = 'corhaste2' order by valor asc");
              @endphp
             <select class="form-control" name="corponteirajde" >
                 <option value="{{$item[0]->cor_ponteira_jde}}">{{$item[0]->cor_ponteira_jde_valor}} </option>
                @foreach ($corponteira as $corponteiras)
                <option value="{{$corponteiras->codigo}}">{{$corponteiras->valor}}</option>
                @endforeach
             </select></tr>
             <tr><th><br>Logo</th></tr>
              <tr><td>@php 
              $corlogo = \DB::select("select codigo, valor from caracteristicas where campo = 'corarm1' order by valor asc");
              @endphp
             <select class="form-control" name="corlogojde" >
                <option value="{{$item[0]->cor_logo_jde}}">{{$item[0]->cor_logo_jde_valor}} </option> 
                @foreach ($corlogo as $corlogos)
                <option value="{{$corlogos->codigo}}">{{$corlogos->valor}}</option>
                @endforeach
             </select></td></tr>
              <tr><th><br>Lente</th></tr>
              <tr><td>@php 
              $corteclente = \DB::select("select codigo, valor from caracteristicas where campo = 'corteclente' order by valor asc");
              @endphp
             <select class="form-control" name="corteclentejde" >
                 <option value="{{$item[0]->cor_lente_jde}}">{{$item[0]->cor_lente_jde_valor}} </option>
                @foreach ($corteclente as $corteclentes)
                <option value="{{$corteclentes->codigo}}">{{$corteclentes->valor}}</option>
                @endforeach
             </select></td></tr>
             <tr><th><br>Quantidade</th></tr>
              <tr><td><input type="number" name="" disabled class="form-control">
              </input></td></tr>
              <tr><th>Custo</th></tr>
              <tr><td><input type="decimal" name="" disabled class="form-control">
              </input></td></tr>
			</table>	
            </div>
        <br>
    
        <div class="footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
</div>
   
     </div>

 
</form>

 
</div>
	  </div>




   

@stop
