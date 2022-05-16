

@extends('layout.principal')

@section('title')

@append 

@section('conteudo')

@if (Session::has('alert-success'))
  <div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 


<div class="row" >	
  <div class="col-md-12" >

    <div class="box box-widget">
      <div class="box-header with-border">
        <table class="table borderless">
			<td class="text-middle" ><i class="fa fa-tripadvisor"></i> <b>Modelo </b>Novo <br></td>
       
	 <th class="text-right">
		 <b>Tipo</b>
		 </th>
		 <th class="text-left">
	 
		<form action="/compras/pedido/modelo/novo/salva" method="post" class="form-horizontal">
	 @csrf
			 <select class="form-control" name="tipo" required >
                <option value=""></option>
                <option value="necessidade">NECESSIDADE</option>
                <option value="conceito">CONCEITO</option>
                <option value="modelo">MODELO</option>
             </select></th>
	 
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
              <td>   @php 
              $id_compra = \DB::select("select id from compras where tipo = 'cotacao' and status = 'aberto' ");
              @endphp
             <select class="form-control" name="id_compra" >
                <option value="{{$compras[0]->id}}">{{$compras[0]->id}}</option>
                @foreach ($id_compra as $id)	
                <option value="{{$id->id}}">{{$id->id}}</option>
                @endforeach
             </select>
           </td>
              <td> @php 
              $marca = \DB::select("select codigo, valor from caracteristicas where campo = 'grife'");
              @endphp
             <select class="form-control" name="grife" required>
                <option value=""></option>
                @foreach ($marca as $marcas)
                <option value="{{$marcas->codigo}}">{{$marcas->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $agrupamento = \DB::select("select valor from caracteristicas where campo = 'agrupamento'");
              @endphp
             <select class="form-control" name="agrupamento" required >
               <option value=""></option>
                @foreach ($agrupamento as $agrupamentos)
                <option value="{{$agrupamentos->valor}}">{{$agrupamentos->valor}}</option>
                @endforeach
             </select></td>
              <td></td>
              <td>  </td>
			  <td>@php 
              $fornecedor = \DB::select("select valor, codigo from caracteristicas where campo = 'fornecedor'");
              @endphp
             <select class="form-control" name="fornecedor" required >
             <option value="{{$compras[0]->id_fornecedor}}">{{$compras[0]->nome}}</option>   
                @foreach ($fornecedor as $fornecedores)
                <option value="{{$fornecedores->codigo}}">{{$fornecedores->valor}}</option>
                @endforeach
             </select></td>
            </tr>
			  <tr > <td colspan="6"></td></tr>
			 
			  
			 <tr  class="card-header bg-info text-center">
              <td><b>Tipo modelo</b></td>
              
              <td><b>Cod modelo</b></td>
              <td><b>Cod fabrica</b></td>
              <td><b>NCM</b></td>
              <td><b>Clasmod</b></td>
              <td><b>Col modelo</b></td>
            </tr>
            <tr class="text-center">
              <td>@php 
              $linha = \DB::select("select valor from caracteristicas where campo = 'linha'");
              @endphp
             <select class="form-control" name="tipomodelo" required >
             <option value=""></option>
                @foreach ($linha as $linhas)
                <option value="{{$linhas->valor}}">{{$linhas->valor}}</option>
                @endforeach
             </select></td>
              @php
               $ultimocod = \DB::select("select id from compras_modelos order by id desc limit 1");
               @endphp
              <td><input type="text" name="modelo"class="form-control" value="{{$ultimocod[0]->id}}"></input></td>
			
              <td><input type="text" name="codfabrica"   class="form-control" value="{{$ultimocod[0]->id}}"></input></td>
              <td><select class="form-control" name="ncm" required >
             <option value=""></option>
                <option value="90031910">RX - metal</option>
                <option value="90031100">RX - Plastico/Acetato</option>
                <option value="90041000">Solar</option>
             </select></td>
              <td> @php 
              $classmod = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'clasmod' and ltrim(rtrim(valor)) in ('linha a', 'linha a+', 'linha a++', 'linha a-', 'promocional c', 'coleção b')");
              @endphp
             <select class="form-control" name="clasmod"	required >
             <option value=""></option>
                @foreach ($classmod as $classmods)
                <option value="{{$classmods->valor}}">{{$classmods->valor}}</option>
                @endforeach
             </select></td>
			  <td>@php 
              $colmod = \DB::select("select valor, codigo from caracteristicas where campo = 'colmod' order by codigo desc");
              @endphp
             <select class="form-control" name="colmod" required >
             <option value=""></option>
                @foreach ($colmod as $colmods)
                <option value="{{$colmods->valor}}">{{$colmods->valor}}</option>
                @endforeach
             </select></td>
            </tr>
			  
			   <tr > <td colspan="6"></td></tr>
			  
			  <tr  class="card-header bg-info text-center">
			<td><b>Ano modelo</b></td>
              <td><b>Idade</b></td>
              <td><b>Linha de estilo</b></td>
              <td><b>Categoria</b></td>
			 <td><b>Tema</b></td>
              <td><b>Genero</b></td>
            </tr>
            <tr class="text-center">
              <td>@php 
              $anomod = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'anomod' order by codigo desc");
              @endphp
             <select class="form-control" name="anomod" required >
             <option value=""></option>
                @foreach ($anomod as $anomods)
                <option value="{{$anomods->valor}}">{{$anomods->valor}}</option>
                @endforeach
             </select></td>
             
            <td>@php 
              $idade = \DB::select("select valor, codigo from caracteristicas where campo = 'idade'");
              @endphp
             <select class="form-control" name="idade"required >
             <option value=""></option>
                @foreach ($idade as $idades)
                <option value="{{$idades->codigo}}">{{$idades->valor}}</option>
                @endforeach
             </select></td>
            <td>@php 
              $estilo = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'estilo'");
              @endphp
             <select class="form-control" name="linha"  >
             <option value=""></option>
                @foreach ($estilo as $estilos)
                <option value="{{$estilos->valor}}">{{$estilos->valor}}</option>
                @endforeach
             </select></td>
			<td><select class="form-control" name="categoria"  >
             <option value=""></option>
				<option value="basic"> Basic</option>
				<option value="medium"> Medium</option>
				<option value="premium"> Premium</option>
                
             </select> </td>
			<td><input type="text" name="colecaoestilo"  class="form-control"></input></td>
              <td>@php 
              $genero = \DB::select("select valor, codigo from caracteristicas where campo = 'genero'");
              @endphp
             <select class="form-control" name="genero" required >
             <option value=""></option>
                @foreach ($genero as $generos)
                <option value="{{$generos->codigo}}">{{$generos->valor}}</option>
                @endforeach
             </select></td>
			
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
              <td><select class="form-control" name="aprovacaoconceito" required >
             <option value=""></option>
               
                <option value="aprovado">Aprovado</option>
				<option value="reprovado">Reprovado</option>
				 <option value="alterando">Alterando</option>
				 <option value="em análise">Em análise</option>
               
             </select></td>
              <td><select class="form-control" name="aprovacaoprototipodesign" required >
             <option value=""></option>
               
                <option value="aprovado">Aprovado</option>
				<option value="reprovado">Reprovado</option>
				 <option value="Aguardando">Aguardando</option>
				 <option value="sem necessidade">Sem necessidade</option>
               
             </select></td>
               <td><select class="form-control" name="aprovacaoprototipocores" required >
             <option value=""></option>
               
                <option value="aprovado">Aprovado</option>
				<option value="reprovado">Reprovado</option>
				 <option value="Aguardando">Aguardando</option>
				 <option value="sem necessidade">Sem necessidade</option>
               
             </select></td>
              
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
              <td><input type="text" name="codmoldefrente"   class="form-control"></input></td>
              <td> @php 
              $materialf = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'material'");
              @endphp
             <select class="form-control" name="materialfrente" required >
             <option value=""></option>
                @foreach ($materialf as $materialfs)
                <option value="{{$materialfs->valor}}">{{$materialfs->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $corteclente = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'fixacao'");
              @endphp
             <select class="form-control" name="materiallente"  >
             <option value=""></option>
                @foreach ($corteclente as $corteclentes)
                <option value="{{$corteclentes->valor}}">{{$corteclentes->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $fixacao = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'fixacao'");
              @endphp
             <select class="form-control" name="fixacao" required >
             <option value=""></option>
                @foreach ($fixacao as $fixacaos)
                <option value="{{$fixacaos->valor}}">{{$fixacaos->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $formato = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'corteclente'");
              @endphp
             <select class="form-control" name="formato"  >
             <option value=""></option>
                @foreach ($formato as $formatos)
                <option value="{{$formatos->valor}}">{{$formatos->valor}}</option>
                @endforeach
             </select> </td>
			  <td><select class="form-control" name="curvatura"  >
             <option value=""></option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
             </select></td>
            </tr>
			  <tr > <td colspan="6"></td></tr>
			  <tr  class="card-header bg-info text-center">
              <td><b>Tamanho lente</b></td>
              <td><b>Tamanho Frente</b></td>
              <td><b>Altura lente</b></td>
              <td><b>Plaqueta</b></td>
              <td><b>Material Plaqueta</b></td>
              <td><b>Tamanho ponte</b></td>
            </tr>
            <tr class="text-center">
              <td>@php 
              $tamanhoolho = \DB::select("select REPLACE( REPLACE( valor, '.' ,'' ), ',', '.' ) as valor, codigo from caracteristicas where campo = 'tamolho'");
              @endphp
             <select class="form-control" name="tamanholente"  >
             <option value="0"></option>
                @foreach ($tamanhoolho as $tamanhoolhos)
                <option value="{{$tamanhoolhos->valor}}">{{$tamanhoolhos->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $tamanhofrente = \DB::select("select REPLACE( REPLACE( valor, '.' ,'' ), ',', '.' ) as valor, codigo from caracteristicas where campo = 'tamolho'");
              @endphp
             <select class="form-control" name="tamanhofrente"  >
             <option value="0"></option>
                @foreach ($tamanhofrente as $tamanhofrentes)
                <option value="{{$tamanhofrentes->valor}}">{{$tamanhofrentes->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $alturalente = \DB::select("select REPLACE( REPLACE( valor, '.' ,'' ), ',', '.' ) as valor, codigo from caracteristicas where campo = 'tamolho'");
              @endphp
             <select class="form-control" name="alturalente" >
             <option value="0"></option>
                @foreach ($alturalente as $alturalentes)
                <option value="{{$alturalentes->valor}}">{{$alturalentes->valor}}</option>
                @endforeach
             </select></td>
              <td><select class="form-control" name="plaqueta"  >
             <option value=""></option>
                <option value="sim">Sim</option>
                <option value="Não">Não</option>
                
             </select></td>
              <td> 
            @php 
              $materialp = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'material'");
              @endphp
             <select class="form-control" name="materialplaqueta"  >
             <option value=""></option>
                @foreach ($materialp as $materialps)
                <option value="{{$materialps->valor}}">{{$materialps->valor}}</option>
                @endforeach
             </select></td>
			  <td>@php 
              $tamponte = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'tamponte'");
              @endphp
             <select class="form-control" name="tamanhoponte"  >
             <option value="0"></option>
                @foreach ($tamponte as $tampontes)
                <option value="{{$tampontes->valor}}">{{$tampontes->valor}}</option>
                @endforeach
             </select></td>
            </tr>
			 <tr > <td colspan="6"></td></tr>
			  <tr  class="card-header bg-info text-center">
              <td><b>Graduação</b></td>

            </tr>
            <tr class="text-center">
              <td><select class="form-control" name="graduacao" required >
             <option value=""></option>
                <option value="Sim">Sim</option>
                <option value="Não">Não</option>
             </select></td>

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
              <td><input type="text" name="codmoldehaste"  class="form-control"></input></td>
              <td> @php 
              $materialh = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'material'");
              @endphp
             <select class="form-control" name="materialhaste" required >
             <option value=""></option>
                @foreach ($materialh as $materialhs)
                <option value="{{$materialhs->valor}}">{{$materialhs->valor}}</option>
                @endforeach
             </select></td>
              <td> @php 
              $tamanhohaste = \DB::select("select REPLACE( REPLACE( valor, '.' ,'' ), ',', '.' ) as valor, codigo from caracteristicas where campo = 'tamhaste'");
              @endphp
             <select class="form-control" name="tamanhohaste"  >
             <option value="0"></option>
                @foreach ($tamanhohaste as $tamanhohastes)
                <option value="{{$tamanhohastes->valor}}">{{$tamanhohastes->valor}}</option>
                @endforeach
             </select></td>
              <td> @php 
              $materiall = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'material'");
              @endphp
             <select class="form-control" name="materialponteira"  >
             <option value=""></option>
                @foreach ($materiall as $materialls)
                <option value="{{$materialls->valor}}">{{$materialls->valor}}</option>
                @endforeach
             </select></td>
              <td>@php 
              $materiall = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'material'");
              @endphp
             <select class="form-control" name="materiallogo"  >
             <option value=""></option>
                @foreach ($materiall as $materialls)
                <option value="{{$materialls->valor}}">{{$materialls->valor}}</option>
                @endforeach
             </select> </td>
			  <td> @php 
              $tecnologia = \DB::select("select ltrim(rtrim(valor)) as valor, codigo from caracteristicas where campo = 'tecnologia'");
              @endphp
             <select class="form-control" name="tecnologia" >
             <option value=""></option>
                @foreach ($tecnologia as $tecnologias)
                <option value="{{$tecnologias->valor}}">{{$tecnologias->valor}}</option>
                @endforeach
             </select></td>
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
              
              <td><textarea id="lentedireita"  name="lentedireita" rows="2" cols="10" class="form-control"></textarea></td>
              <td><textarea id="lenteesquerda"  name="lenteesquerda" rows="2" cols="10" class="form-control"></textarea></td>
              <td><textarea id="hastedireita" name="hastedireita" rows="2" cols="10" class="form-control"></textarea></td>
              <td><textarea id="hasteesquerda"  name="hasteesquerda" rows="2" cols="20" class="form-control"></textarea></td>
			  <td><textarea id="gravacaologo"  name="gravacaologo" rows="2" cols="20" class="form-control"></textarea></td>
            </tr>
			  
          </table>
			 
	
    
	 
	 
	 
	 
	 
	 
   
<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      
</form>


</div>




   

@stop
