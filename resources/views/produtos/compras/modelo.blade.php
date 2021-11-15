 @extends('layout.principal')
@section('title')
<!-- <i class="fa fa-file-o"></i> Modelo AT1240-->
@append 

@section('conteudo')

@if (Session::has('alert-success'))
<div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 
@if (Session::has('alert-warning'))
<div class="callout callout-warning">
  <h3><i class="fa fa-warning"></i> Erro</h3>
  {!!Session::get('alert-warning')!!}</div>
@endif
<div class="row" >
<div class="col-md-12" >
<div class="box box-widget">
  <div class="box-header with-border">
    <table class="table borderless">
      
        <td class="text-middle" ><i class="fa fa-tripadvisor"></i> <b>Modelo</b> {{Strtoupper($modelo[0]->modelo_go)}} <br>
          <b>Id</b>{{Strtoupper($modelo[0]->idmodelo)}}</td>
        <th class="text-right"> <b>Tipo</b> </th>
        <td class="text-left"><smal>
          {{Strtoupper($modelo[0]->tipo_ficha)}} </td>
        <th class="text-right"> <button type="button" class="btn btn-default btn-block" data-target="#modalAcoes" data-toggle="modal"><i class="fa fa-exchange"></i> Ações</button></th>
		
		<th class="text-right"> <button type="button" class="btn btn-primary btn-block" data-target="#modalhistorico" data-toggle="modal"><i class="fa fa-file-text-o"></i> Histórico</button></th>
    </table>
  </div>
  <div class="box-body">
    <div class="box box-warning">
      <h3 class="box-title">Dados</h3>
      <table class="table table-bordered table-condensed">
        <tr  class="card-header bg-info text-center">
          <td><b>Id compra</b></td>
          <td><b>Grife</b></td>
          <td><b>Agrupamento</b></td>
          <td><b>Origem</b></td>
          <td><b>Pais</b></td>
          <td><b>Fornecedor</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->id_compra)}}</td>
          <td>{{Strtoupper($modelo[0]->grife)}}</td>
          <td>{{Strtoupper($modelo[0]->agrupamento)}}</td>
          <td>@if ($modelo[0]->pais<>'br')
            {{Strtoupper('importado')}}
            @else
            {{Strtoupper('nacional')}}
            @endif</td>
          <td> {{Strtoupper($modelo[0]->pais)}} </td>
          <td>{{Strtoupper($modelo[0]->fornecedor)}} </td>
        </tr>
        <tr >
          <td colspan="6"></td>
        </tr>
        <tr  class="card-header bg-info text-center">
          <td><b>Tipo modelo</b></td>
          <td><b>Cod modelo</b></td>
          <td><b>Cod fabrica</b></td>
          <td><b>NCM</b></td>
          <td><b>Clasmod</b></td>
          <td><b>Col modelo</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->tipo_modelo)}}</td>
          <td>{{Strtoupper($modelo[0]->modelo_go)}}</td>
          <td>{{Strtoupper($modelo[0]->cod_fabrica)}}</td>
          <td>{{Strtoupper($modelo[0]->ncm)}} </td>
          <td>{{Strtoupper($modelo[0]->class_mod)}}</td>
          <td>{{Strtoupper($modelo[0]->col_mod)}}</td>
        </tr>
        <tr >
          <td colspan="6"></td>
        </tr>
        <tr  class="card-header bg-info text-center">
          <td><b>Ano modelo</b></td>
          <td><b>Idade</b></td>
          <td><b>Linha de estilo</b></td>
          <td><b>Categoria $</b></td>
          <td><b>Tema</b></td>
          <td><b>Genero</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->ano_mod)}}</td>
          <td>{{Strtoupper($modelo[0]->idade)}}</td>
          <td>{{Strtoupper($modelo[0]->linha)}} </td>
          <td>{{Strtoupper($modelo[0]->faixa)}} </td>
          <td>{{Strtoupper($modelo[0]->col_estilo)}}</td>
          <td>{{Strtoupper($modelo[0]->genero)}}</td>
        </tr>
      </table>
    </div>
    <div class="box box-sucess">
      <h3 class="box-title">Aprovações</h3>
      <table class="table table-bordered table-condensed">
        <tr  class="card-header bg-info text-center">
          <td><b>Conceito</b></td>
          <td><b>Protótipo design</b></td>
          <td><b>Protótipo cores</b></td>
        </tr>
        <tr class="text-center">
          <td><span href="">{{Strtoupper($modelo[0]->aprovacao_conceito)}}</span></td>
          <td>{{Strtoupper($modelo[0]->aprovacao_prototipo_design)}}</td>
          <td>{{Strtoupper($modelo[0]->aprovacao_prototipo_cores)}}</td>
        </tr>
      </table>
    </div>
    <div class="box box-danger">
      <h3 class="box-title">Frente</h3>
      <table class="table table-bordered table-condensed">
        <tr  class="card-header bg-info text-center">
          <td><b>Cod molde Frente</b></td>
          <td><b>Material frente</b></td>
          <td><b>Material lente</b></td>
          <td><b>Fixação</b></td>
          <td><b>Formato</b></td>
          <td><b>Curvatura lente</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->cod_molde_frente)}}</td>
          <td>{{Strtoupper($modelo[0]->material_frente)}}</td>
          <td>{{Strtoupper($modelo[0]->material_lente)}}</td>
          <td>{{Strtoupper($modelo[0]->fixacao)}}</td>
          <td>{{Strtoupper($modelo[0]->formato)}} </td>
          <td>{{Strtoupper($modelo[0]->curvatura_lente)}}</td>
        </tr>
        <tr >
          <td colspan="6"></td>
        </tr>
        <tr  class="card-header bg-info text-center">
          <td><b>Tamanho lente</b></td>
          <td><b>Tamanho Frente</b></td>
          <td><b>Altura lente</b></td>
          <td><b>Plaqueta</b></td>
          <td><b>Material Plaqueta</b></td>
          <td><b>Tamanho ponte</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->tamanho_lente)}}</td>
          <td>{{Strtoupper($modelo[0]->tamanho_frente)}}</td>
          <td>{{Strtoupper($modelo[0]->altura_lente)}}</td>
          <td>{{Strtoupper($modelo[0]->plaqueta)}}</td>
          <td>{{Strtoupper($modelo[0]->material_plaqueta)}}</td>
          <td>{{Strtoupper($modelo[0]->tamanho_ponte)}}</td>
        </tr>
        <tr >
          <td colspan="6"></td>
        </tr>
        <tr  class="card-header bg-info text-center">
          <td><b>Graduação</b></td>
        </tr>
        <tr class="text-center">
          <td>{{Strtoupper($modelo[0]->graduacao)}}</td>
        </tr>
      </table>
    </div>
    <div class="box box-success">
      <h3 class="box-title">Haste</h3>
      <table class="table table-bordered table-condensed">
        <tr  class="card-header bg-info text-center">
          <td><b>Cod Molde haste</b></td>
          <td><b>Material haste</b></td>
          <td><b>Tamanho haste</b></td>
          <td><b>Material ponteira</b></td>
          <td><b>Material logo</b></td>
          <td><b>Tecnologia</b></td>
        </tr>
        <tr class="text-center">
          <td><a href="xxx">{{Strtoupper($modelo[0]->cod_molde_haste)}}</a></td>
          <td>{{Strtoupper($modelo[0]->material_haste)}}</td>
          <td>{{Strtoupper($modelo[0]->tamanho_haste)}}</td>
          <td>{{Strtoupper($modelo[0]->material_ponteira)}}</td>
          <td>{{Strtoupper($modelo[0]->material_logo)}} </td>
          <td>{{Strtoupper($modelo[0]->tecnologia)}}</td>
        </tr>
      </table>
    </div>
    <div class="box box-warning">
      <h3 class="box-title">Informações produção</h3>
      <table class="table table-bordered table-condensed">
        <tr  class="card-header bg-info text-center">
          <td><b>Lente Direita</b></td>
          <td><b>Lente Esquerda</b></td>
          <td><b>Haste Direita</b></td>
          <td><b>Haste Esquerda</b></td>
          <td><b>Posição logo</b></td>
        </tr>
        <tr class="text-center">
          <td> {{Strtoupper($modelo[0]->gravacao_lente_direita)}}</td>
          <td>{{Strtoupper($modelo[0]->gravacao_lente_esquerda)}}</td>
          <td>{{Strtoupper($modelo[0]->gravacao_haste_direita)}}</td>
          <td>{{Strtoupper($modelo[0]->gravacao_haste_esquerda)}}</td>
          <td>{{Strtoupper($modelo[0]->gravacao_logo)}}</td>
        </tr>
      </table>
      <buton ><a  align="left"  class="btn btn-default" href="/compras/pedido/modelo/edita/{{$modelo[0]->idmodelo}}">Edita</a></buton>
    </div>
  </div>
</div>
<br>
<div class="col-md-12" >
  <div class="box box-widget">
    <div class="box-header with-border">
	<div class="col-md-3" >
     <button type="button" class="btn btn-default btn-block" data-target="#modalinspiracao_modelo" data-toggle="modal"><i class="fa fa-archive"></i> Inspiração modelo 
			  @if ( $modelo[0]->inspiracao_modelo>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->inspiracao_modelo}} Históricos" class="badge bg-blue">{{$modelo[0]->inspiracao_modelo}}</span>
			    @endif</button>
		</div>
		<div class="col-md-3" >
			
			<button type="button" class="btn btn-default btn-block" data-target="#modaldesenho_tecnico_pdf" data-toggle="modal"><i class="fa fa-archive"></i> Desenho Técnico PDF
				@if ( $modelo[0]->desenho_tecnico_pdf>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->desenho_tecnico_pdf}} Históricos" class="badge bg-blue">{{$modelo[0]->desenho_tecnico_pdf}}</span>
			    @endif</button>
			</div>
		<div class="col-md-3" >
			
			<button type="button" class="btn btn-default btn-block" data-target="#modaldesenho_tecnico_dwg" data-toggle="modal"><i class="fa fa-archive"></i> Desenho Téncico DWG/DXF
				@if ( $modelo[0]->desenho_tecnico_dwg>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->desenho_tecnico_dwg}} Históricos" class="badge bg-blue">{{$modelo[0]->desenho_tecnico_dwg}}</span>
			    @endif</button>
			</div>
		<div class="col-md-3" >
			<button type="button" class="btn btn-default btn-block" data-target="#modalfoto_prototipo" data-toggle="modal"><i class="fa fa-camera"></i> Foto Protótipo
				@if ( $modelo[0]->foto_prototipo>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->foto_prototipo}} Históricos" class="badge bg-blue">{{$modelo[0]->foto_prototipo}}</span>
				@endif</button></br>
			</div>
		<div class="col-md-3" >
			<button type="button" class="btn btn-default btn-block" data-target="#modalfoto_combinacao" data-toggle="modal"><i class="fa fa-camera"></i> Foto_combinacao
				@if ( $modelo[0]->foto_combinacao>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->foto_combinacao}} Históricos" class="badge bg-blue">{{$modelo[0]->foto_combinacao}}</span>
			    @endif</button>
			</div>
		<div class="col-md-3" >
			<button type="button" class="btn btn-default btn-block" data-target="#modalreferencia_cores" data-toggle="modal"><i class="fa fa-archive"></i> Referência cores
				@if ( $modelo[0]->referencia_cores>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->referencia_cores}} Históricos" class="badge bg-blue">{{$modelo[0]->referencia_cores}}</span>
			  </div>
		<div class="col-md-3" >  @endif</button>
			
			<button type="button" class="btn btn-default btn-block" data-target="#modalpatern" data-toggle="modal"><i class="fa fa-archive"></i> Patern
				@if ( $modelo[0]->patern>0)
		        <span data-toggle="tooltip" title="{{$modelo[0]->patern}} Históricos" class="badge bg-blue">{{$modelo[0]->patern}}</span>
				@endif</button>
			</div>
		
          
        
    </div>
  </div>
</div>
<div class="col-md-12" >
  <div class="box box-widget">
    <div class="box-header with-border">
      <div class="box box-primary">
		  </br>
		<div class="col-md-10"><h4 class="box-title">Cores</h4></div>
		
		  
        <div class="col-md-2"><button type="button" class="btn btn-success btn-block" data-target="#modalNovaCor" data-toggle="modal"><i class="fa fa-plus"></i> Nova cor</button></div>
	 
        <table class="table table-bordered table-condensed">
          <tr>
            <th>Foto</th>
            <th>Id</th>
            <th>Cod</th>
            <th>Cod Fornecedor</th>
            <th>Clas cor</th>
            <th>Frente 1ª</th>
            <th>Frente 2ª</th>
            <th>Frente 3ª</th>
            <th>Haste 1ª</th>
            <th>Haste 2ª</th>
            <th>Haste 3ª</th>
            <th>Ponteira</th>
            <th>Logo</th>
            <th>Lente</th>
            <th>Custo</th>
            <th>Sugestão de compra</th>
            <th>Qtd efetiva</th>
          </tr>
          @foreach($itens as $item)
          <tr>
            <td><a href="" class="zoom" data-value="ref"> <img  width="50" src="https://portal.goeyewear.com.br/teste999.php?referencia=ref" class="img-responsive"> </a></td>
            <td><a href="/compras/pedido/item/edita/{{$item->id}}">
              <button type="button" class="btn btn-success btn-block" ><i class="fa fa-exchange"></i> {{Strtoupper($item->id)}}</button>
              </a></td>
            <td>{{Strtoupper($item->cod_cor)}}</td>
            <td>{{Strtoupper($item->cod_cor_fornecedor)}}</td>
            <td>{{Strtoupper($item->clasitem)}}</td>
            <td>{{Strtoupper($item->cor_frente1)}}</td>
            <td>{{Strtoupper($item->cor_frente2)}}</td>
            <td>{{Strtoupper($item->cor_frente3)}}</td>
            <td>{{Strtoupper($item->cor_haste1)}}</td>
            <td>{{Strtoupper($item->cor_haste2)}}</td>
            <td>{{Strtoupper($item->cor_haste3)}}</td>
            <td>{{Strtoupper($item->cor_ponteira)}}</td>
            <td>{{Strtoupper($item->cor_logo)}}</td>
            <td>{{Strtoupper($item->cor_lente)}}</td>
            <td>{{Strtoupper($item->custo)}}</td>
            @php
            $sugestoes = \DB::select("
            select ifnull(sug_compra,0) as sug
            from sugestoes
            where agrup = '{$modelo[0]->agrupamento}'
            and clas_mod = '{$modelo[0]->class_mod}'
            and idade = ''
            and material = ''
            and genero = ''
            and clas_item = '{$item->clasitem}'");
            if(count($sugestoes)>0){
            $sugestao = $sugestoes[0]->sug;}
            
            else {$sugestao = '0';
            
            }
            @endphp
            <td>{{Strtoupper($sugestao)}}</td>
            <td>{{Strtoupper($item->quantidade)}}</td>
			  <td><a href="/compras/pedido/item/exclui/{{$item->id}}" class="btn btn-danger btn-block"><i class="fa fa-remove"></i>Exclui</a></td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>
<form action="/compras/pedido/item/novo" id="frmNovaCor" class="form-horizontal" method="post" 	enctype="multipart/form-data">
  @csrf
  <div class="modal fade" id="modalNovaCor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Nova cor</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_modelo" value="{{$modelo[0]->idmodelo}}">
          <div class="form-group">
            <div class="col-md-12">
				<div class="col-md-6">
					<h3 align="center">Fábrica</h3>
					<table >
			<tr><th>Cod cor Fabrica</th></tr>
              <tr><td><input type="text" name="codcorfabrica" class="form-control">
              </input></td></tr>
						
              <tr><th>Cod cor GO</th></tr>
              <tr><td><input type="text" name="codcorgo" class="form-control">
              </input></td></tr>
			<tr><th>Classificação item</th></tr>
              @php 
              $classmod = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'clasmod'");
              @endphp
              <tr><td><select class="form-control" name="clasitem" >
                <option value=""> </option>
			 @foreach ($classmod as $classmods)
			<option value="{{$classmods->valor}}">{{Strtoupper($classmods->valor)}}</option>
                 @endforeach
 			</select></td></tr>
			<tr><th>Cor frente 1</th></tr>
              <tr><td><textarea id="corfrente1" required name="corfrente1" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th> Cor frente 2</th></tr>
              <tr><td><textarea id="corfrente2" name="corfrente2" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
              <tr><th>Cor frente 3</th></tr>
              <tr><td><tr><td><textarea id="corfrente3" name="corfrente3" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th>Cor haste 1</th></tr>
              <tr><td><textarea id="corhaste1" required name="corhaste1" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th>Cor haste 2</th></tr>
              <tr><td><textarea id="corhaste2" name="corhaste2" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
              <tr><th>Cor haste 3</th></tr>
              <tr><td><textarea id="corhaste3" name="corhaste3" rows="2" cols="30" class="form-control">
				</textarea><strong></tr></td></strong>
             <tr><th>Ponteira</th></tr>
              <tr><td><textarea id="corponteira" name="corponteira" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th>Logo</th></tr>
              <tr><td><textarea id="corlogo" required name="corlogo" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
              <tr><th>Lente</th></tr>
              <tr><td><textarea id="corlente" required name="corlente" rows="2" cols="30" class="form-control">
				</textarea></td></tr>
             <tr><th>Quantidade</th></tr>
              <tr><td><input type="number" name="quantidade" class="form-control">
              </input></td></tr>
              <tr><th>Custo</th></tr>
              <tr><td><input type="decimal" name="custo" class="form-control">
              </input></td></tr>
			</table>
            </div>
			 
	  <div class-"col-md-6">
		  <h3 align="center">JDE</h3>
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
                <option value=""></option>
                @foreach ($corarm1 as $corarm1s)
                <option value="{{$corarm1s->codigo}}">{{$corarm1s->valor}}</option>
                @endforeach
             </select></td></tr>
             <tr><th><br> Cor frente 2</th></tr>
              <tr><td>@php 
              $corarm2 = \DB::select("select codigo, valor from caracteristicas where campo = 'corarm2' order by valor asc");
              @endphp
             <select class="form-control" name="corarm2jde" >
                <option value=""></option>
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
                <option value=""></option>
                @foreach ($corhaste1 as $corhaste1s)
                <option value="{{$corhaste1s->codigo}}">{{$corhaste1s->valor}}</option>
                @endforeach
             </select></td></tr>
             <tr><th><br>Cor haste 2</th></tr>
              <tr><td>@php 
              $corhaste2 = \DB::select("select codigo, valor from caracteristicas where campo = 'corhaste2' order by valor asc");
              @endphp
             <select class="form-control" name="corhaste2jde" >
                <option value=""></option>
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
                <option value=""></option>
                @foreach ($corponteira as $corponteiras)
                <option value="{{$corponteiras->codigo}}">{{$corponteiras->valor}}</option>
                @endforeach
             </select></tr>
             <tr><th><br>Logo</th></tr>
              <tr><td>@php 
              $corlogo = \DB::select("select codigo, valor from caracteristicas where campo = 'corarm1' order by valor asc");
              @endphp
             <select class="form-control" name="corlogojde" >
                <option value=""></option>
                @foreach ($corlogo as $corlogos)
                <option value="{{$corlogos->codigo}}">{{$corlogos->valor}}</option>
                @endforeach
             </select></td></tr>
              <tr><th><br>Lente</th></tr>
              <tr><td>@php 
              $corteclente = \DB::select("select codigo, valor from caracteristicas where campo = 'corteclente' order by valor asc");
              @endphp
             <select class="form-control" name="corteclentejde" >
                <option value=""></option>
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
		  </div>
	  </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</form>
<form action="/compras/pedido/modelo/copiar" id="frmAcoes" class="form-horizontal" method="post" 	enctype="multipart/form-data">
  @csrf
  <div class="modal fade" id="modalAcoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Ações</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">
          <div class="form-group">
            <div class="col-md-12">
              <table class="table table-bordered table-striped">
                @php
                $grife = $modelo[0]->grife;
                $modelos = \DB::select("select id, modelo_go from compras_modelos where grife = '$grife'  ");
                @endphp
                <tr>
                  <td> Copiar para o modelo<br>
                    <select required class="form-control" name="infomodelo" >
						 <option value=""></option>
                      <option value="novo">Novo</option>
                      
             @foreach($modelos as $modelo1)
			 
                      <option value="{{$modelo1->id}}">{{Strtoupper($modelo1->id.' - '.$modelo1->modelo_go)}}</option>
                      
			@endforeach
                    </select></td>
                </tr>
<!--				  <tr style="text-align: center;"><td><input type="checkbox" id="seleAll"></td></tr> -->
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tipo_ficha' VALUE='{{$modelo[0]->tipo_ficha}}'>
                    <B>{{Strtoupper('tipo_ficha - ')}}</B>{{Strtoupper($modelo[0]->tipo_ficha)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='id_compra' VALUE='{{$modelo[0]->id_compra}}'>
                    <B>{{Strtoupper('id_compra - ')}}</B>{{Strtoupper($modelo[0]->id_compra)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tipo' VALUE='{{$modelo[0]->tipo}}'>
                    <B>{{Strtoupper('tipo - ')}}</B>{{Strtoupper($modelo[0]->tipo)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='id_fornecedor' VALUE='{{$modelo[0]->id_fornecedor}}'>
                    <B>{{Strtoupper('id_fornecedor - ')}}</B>{{Strtoupper($modelo[0]->id_fornecedor)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='cod_fabrica' VALUE='{{$modelo[0]->cod_fabrica}}'>
                    <B>{{Strtoupper('cod_fabrica - ')}}</B>{{Strtoupper($modelo[0]->cod_fabrica)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='modelo_go' VALUE='{{$modelo[0]->modelo_go}}'>
                    <B>{{Strtoupper('modelo_go - ')}}</B>{{Strtoupper($modelo[0]->modelo_go)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='cod_molde_frente' VALUE='{{$modelo[0]->cod_molde_frente}}'>
                    <B>{{Strtoupper('cod_molde_frente - ')}}</B>{{Strtoupper($modelo[0]->cod_molde_frente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='cod_molde_haste' VALUE='{{$modelo[0]->cod_molde_haste}}'>
                    <B>{{Strtoupper('cod_molde_haste - ')}}</B>{{Strtoupper($modelo[0]->cod_molde_haste)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='grife' VALUE='{{$modelo[0]->grife}}'>
                    <B>{{Strtoupper('grife - ')}}</B>{{Strtoupper($modelo[0]->grife)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tipo_modelo' VALUE='{{$modelo[0]->tipo_modelo}}'>
                    <B>{{Strtoupper('tipo_modelo - ')}}</B>{{Strtoupper($modelo[0]->tipo_modelo)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='agrupamento' VALUE='{{$modelo[0]->agrupamento}}'>
                    <B>{{Strtoupper('agrupamento - ')}}</B>{{Strtoupper($modelo[0]->agrupamento)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='ncm' VALUE='{{$modelo[0]->ncm}}'>
                    <B>{{Strtoupper('ncm - ')}}</B>{{Strtoupper($modelo[0]->ncm)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='armazenamento' VALUE='{{$modelo[0]->armazenamento}}'>
                    <B>{{Strtoupper('armazenamento - ')}}</B>{{Strtoupper($modelo[0]->armazenamento)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='ano_mod' VALUE='{{$modelo[0]->ano_mod}}'>
                    <B>{{Strtoupper('ano_mod - ')}}</B>{{Strtoupper($modelo[0]->ano_mod)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='col_mod' VALUE='{{$modelo[0]->col_mod}}'>
                    <B>{{Strtoupper('col_mod - ')}}</B>{{Strtoupper($modelo[0]->col_mod)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='class_mod' VALUE='{{$modelo[0]->class_mod}}'>
                    <B>{{Strtoupper('class_mod - ')}}</B>{{Strtoupper($modelo[0]->class_mod)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='genero' VALUE='{{$modelo[0]->genero}}'>
                    <B>{{Strtoupper('genero - ')}}</B>{{Strtoupper($modelo[0]->genero)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='idade' VALUE='{{$modelo[0]->idade}}'>
                    <B>{{Strtoupper('idade - ')}}</B>{{Strtoupper($modelo[0]->idade)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='linha' VALUE='{{$modelo[0]->linha}}'>
                    <B>{{Strtoupper('linha - ')}}</B>{{Strtoupper($modelo[0]->linha)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='faixa' VALUE='{{$modelo[0]->faixa}}'>
                    <B>{{Strtoupper('faixa - ')}}</B>{{Strtoupper($modelo[0]->faixa)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='col_estilo' VALUE='{{$modelo[0]->col_estilo}}'>
                    <B>{{Strtoupper('col_estilo - ')}}</B>{{Strtoupper($modelo[0]->col_estilo)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tecnologia' VALUE='{{$modelo[0]->tecnologia}}'>
                    <B>{{Strtoupper('tecnologia - ')}}</B>{{Strtoupper($modelo[0]->tecnologia)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_frente' VALUE='{{$modelo[0]->material_frente}}'>
                    <B>{{Strtoupper('material_frente - ')}}</B>{{Strtoupper($modelo[0]->material_frente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_haste' VALUE='{{$modelo[0]->material_haste}}'>
                    <B>{{Strtoupper('material_haste - ')}}</B>{{Strtoupper($modelo[0]->material_haste)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_logo' VALUE='{{$modelo[0]->material_logo}}'>
                    <B>{{Strtoupper('material_logo - ')}}</B>{{Strtoupper($modelo[0]->material_logo)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='plaqueta' VALUE='{{$modelo[0]->plaqueta}}'>
                    <B>{{Strtoupper('plaqueta - ')}}</B>{{Strtoupper($modelo[0]->plaqueta)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_plaqueta' VALUE='{{$modelo[0]->material_plaqueta}}'>
                    <B>{{Strtoupper('material_plaqueta - ')}}</B>{{Strtoupper($modelo[0]->material_plaqueta)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_lente' VALUE='{{$modelo[0]->material_lente}}'>
                    <B>{{Strtoupper('material_lente - ')}}</B>{{Strtoupper($modelo[0]->material_lente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='material_ponteira' VALUE='{{$modelo[0]->material_ponteira}}'>
                    <B>{{Strtoupper('material_ponteira - ')}}</B>{{Strtoupper($modelo[0]->material_ponteira)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='curvatura_lente' VALUE='{{$modelo[0]->curvatura_lente}}'>
                    <B>{{Strtoupper('curvatura_lente - ')}}</B>{{Strtoupper($modelo[0]->curvatura_lente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='formato' VALUE='{{$modelo[0]->formato}}'>
                    <B>{{Strtoupper('formato - ')}}</B>{{Strtoupper($modelo[0]->formato)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='fixacao' VALUE='{{$modelo[0]->fixacao}}'>
                    <B>{{Strtoupper('fixacao - ')}}</B>{{Strtoupper($modelo[0]->fixacao)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='graduacao' VALUE='{{$modelo[0]->graduacao}}'>
                    <B>{{Strtoupper('graduacao - ')}}</B>{{Strtoupper($modelo[0]->graduacao)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tamanho_lente' VALUE='{{$modelo[0]->tamanho_lente}}'>
                    <B>{{Strtoupper('tamanho_lente - ')}}</B>{{Strtoupper($modelo[0]->tamanho_lente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tamanho_frente' VALUE='{{$modelo[0]->tamanho_frente}}'>
                    <B>{{Strtoupper('tamanho_frente - ')}}</B>{{Strtoupper($modelo[0]->tamanho_frente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='altura_lente' VALUE='{{$modelo[0]->altura_lente}}'>
                    <B>{{Strtoupper('altura_lente - ')}}</B>{{Strtoupper($modelo[0]->altura_lente)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tamanho_ponte' VALUE='{{$modelo[0]->tamanho_ponte}}'>
                    <B>{{Strtoupper('tamanho_ponte - ')}}</B>{{Strtoupper($modelo[0]->tamanho_ponte)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='tamanho_haste' VALUE='{{$modelo[0]->tamanho_haste}}'>
                    <B>{{Strtoupper('tamanho_haste - ')}}</B>{{Strtoupper($modelo[0]->tamanho_haste)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='gravacao_lente_direita' VALUE='{{$modelo[0]->gravacao_lente_direita}}'>
                    <B>{{Strtoupper('gravacao_lente_direita - ')}}</B>{{Strtoupper($modelo[0]->gravacao_lente_direita)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='gravacao_lente_esquerda' VALUE='{{$modelo[0]->gravacao_lente_esquerda}}'>
                    <B>{{Strtoupper('gravacao_lente_esquerda - ')}}</B>{{Strtoupper($modelo[0]->gravacao_lente_esquerda)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='gravacao_haste_direita' VALUE='{{$modelo[0]->gravacao_haste_direita}}'>
                    <B>{{Strtoupper('gravacao_haste_direita - ')}}</B>{{Strtoupper($modelo[0]->gravacao_haste_direita)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='gravacao_haste_esquerda' VALUE='{{$modelo[0]->gravacao_haste_esquerda}}'>
                    <B>{{Strtoupper('gravacao_haste_esquerda - ')}}</B>{{Strtoupper($modelo[0]->gravacao_haste_esquerda)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='gravacao_logo' VALUE='{{$modelo[0]->gravacao_logo}}'>
                    <B>{{Strtoupper('gravacao_logo - ')}}</B>{{Strtoupper($modelo[0]->gravacao_logo)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='aprovacao_conceito' VALUE='{{$modelo[0]->aprovacao_conceito}}'>
                    <B>{{Strtoupper('aprovacao_conceito - ')}}</B>{{Strtoupper($modelo[0]->aprovacao_conceito)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='aprovacao_prototipo_design' VALUE='{{$modelo[0]->aprovacao_prototipo_design}}'>
                    <B>{{Strtoupper('aprovacao_prototipo_design - ')}}</B>{{Strtoupper($modelo[0]->aprovacao_prototipo_design)}}</td>
                </tr>
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='aprovacao_prototipo_cores' VALUE='{{$modelo[0]->aprovacao_prototipo_cores}}'>
                    <B>{{Strtoupper('aprovacao_prototipo_cores - ')}}</B>{{Strtoupper($modelo[0]->aprovacao_prototipo_cores)}}</td>
                </tr>
                
                <tr>
                  <td><INPUT TYPE='checkbox' NAME='cor' VALUE='copiar_cor'>
                    <B>CORES</B></td>
                </tr>
                
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</form>

	<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/inspiracao_modelo" id="frminspiracao_modelo" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalinspiracao_modelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Inspirações modelo</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='inspiracao_modelo')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão:{{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>


<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/desenho_tecnico_pdf" id="frmdesenho_tecnico_pdf" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modaldesenho_tecnico_pdf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Desenho Técnico PDF</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='desenho_tecnico_pdf')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão:{{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>

<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/desenho_tecnico_dwg" id="frmdesenho_tecnico_dwg" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modaldesenho_tecnico_dwg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Desenho Técnico DWG</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='desenho_tecnico_dwg')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão:{{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>

<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/foto_prototipo" id="frmfoto_prototipo" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalfoto_prototipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Foto Protótipo</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='foto_prototipo')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão:{{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>

<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/foto_combinacao" id="frmfoto_combinacao" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalfoto_combinacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Foto Combinação</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='foto_combinacao')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão: {{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>

<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/patern" id="frmpatern" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalpatern" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Patern</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='patern')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão: {{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>

<form action="/compras/pedidos/upload/{{$modelo[0]->idmodelo}}/referencia_cores" id="frmreferencia_cores" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalreferencia_cores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Referência cores</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</br>
		
             @foreach ($arquivos as $arquivo)
				@if($arquivo->tipo=='referencia_cores')
		<div class="form-group">
			     <div class="col-xs-12" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Tipo: {{$arquivo->tipo}} </small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usuário: {{$arquivo->nome}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
				<small>Extensão: {{$arquivo->extensao}} </small></br>
		</div>
	</div>
			@if($arquivo->extensao=='png' or $arquivo->extensao=='jpg' or $arquivo->extensao=='pdf' )
 <div class="row" align="center">
			 <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$arquivo->arquivo}}" align="center" width= "800px" height= "600px"></iframe>
</div>
	@else
<div class="row" align="center">
			 <iframe class="pull-center"src="" align="center" width= "800px"></iframe>
</div>

			@endif
		
                
                </a> </div>  </div>
@endif
		@endforeach
            
		
            
	 
		  

      </div>
      
</div>
    </div>
  </div>
</div>
</div>
</form>






<!--
--------------------------
histórico
-->
<form action="/compras/pedidos/historico" id="frmhistorico" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalhistorico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  
		 <div class="col-md-12">
		   <input type="hidden" name="idmodelo" value="{{$modelo[0]->idmodelo}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Obs
				<input type="text" name="obs" class="form-control" required></input>
				Data
	  			<input type="date" name="data" class="form-control" required></input>
	  
				Arquivo
				<input type="file" name="arquivo" class="form-control" required>
				</input>
				</div>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
		  
		  </div>
	</div>
        
      
      <div class="modal-body">

    <!-- row -->
    <div class="row">
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              
              
				
				
          
			  <div class="tab-content">
              <div class="active tab-pane" id="geral">
      <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">


      <li class="time-label">
		  @foreach($historicos as $historico)
            <span class="bg-gray">
             {{$historico->data_historico}}
            </span>
      </li>



			<li>

            <i class="fa fa-envelope bg-gray"></i>
			  
			
            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->hora_historico}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> {{Strtoupper($historico->historico)}}</h3>

              <div class="timeline-body">
				  <div class="post clearfix">  {{Strtoupper($historico->obs)}}</div>




<div class="post clearfix"> 
					@if($historico->extensao=='jpg' or $historico->extensao=='png' or $historico->extensao=='jpeg')

                    <img src="{{$historico->arquivo}}" class="img-responsive">

	              @elseif ($historico->extensao=='pdf')

                      <iframe class="pull-center"src="https://painel.goeyewear.com.br{{$historico->arquivo}}" align="center" width= "600px" height= "500px"></iframe>
				  
				  @elseif ($historico->extensao<>'jpg' or $historico->extensao<>'png' or $historico->extensao<>'pdf' or $historico->extensao<>'jpeg' or $historico->extensao<>'pdf')
				  
				 <br>Arquivo: <a href="{{$historico->arquivo}}" target="_blank">arquivo</a>
			@endif
				  </div>
              </div>
					


              <div class="timeline-footer">
<!--                <a href="/historico//deleta" class="btn btn-danger btn-xs">Delete</a>-->
              </div>
            </div>
			@endforeach

          </li>

        </ul>
		  </div>
				</div>
				  				  				  				  
				  				  				  				  				  				  
    </div>

  </div>

</div>


<!--___ fim -->
			</div>
      

    </div>
  </div>
</div>
</div>
</form>

@stop 