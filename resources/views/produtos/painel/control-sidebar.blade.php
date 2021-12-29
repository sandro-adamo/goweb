 
<!-- Control Sidebar -->
  <form method="get" id="frmFiltro">
  <aside class="control-sidebar control-sidebar-light">

    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		
      <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-filter"></i></a></li>

      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-sort-amount-asc"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane active" id="control-sidebar-settings-tab">

          <h3 class="control-sidebar-heading">Filters <button type="submit" id="aplicarFiltro" class="btn btn-sm btn-default pull-right">Apply</button></h3>


          

          <div class="form-group">  
            <p><a href="" class="retrai" data-value="ano"><i class="fa fa-minus-square-o" data-value="ano"></i> Show</a></p>            
            <div id="show">
              <div class="row">
                <div class="col-md-6">
                  <input type="radio" class="item" name="show" data-value="show" value="model" checked> Model
                </div>
                <div class="col-md-6">
                  <input type="radio" class="item" name="show" data-value="show" value="item"> Item                 
                </div>
              </div>
            </div>
          </div>

{{--
          @php
            $filtros = \DB::select("select campo from caracteristicas where campo not in ('agrupamento', 'classitem', 'classmod', 'corarm2', 'corhaste1', 'corhaste2', 'status', 'tamanhohaste', 'tamanhoolho', 'tamanhoponte', 'tipoarmazenamento') group by campo");

          @endphp


          @foreach ($filtros as $filtro)

            @php

              $valores = \DB::select("select $filtro->campo as valor from itens group by $filtro->campo");


            @endphp

            <div class="form-group">  
              <p><a href="" class="retrai" data-value="ano"><i class="fa fa-minus-square-o" data-value="ano"></i> {{$filtro->campo}}</a> <span class="pull-right"><input type="checkbox" class="all" data-value="anomod"><small>All</small></span></p>            
              <div id="ano">
                <div class="row">

                  @foreach ($valores as $valor)
                  <div class="col-md-6">
                    <label class="control-sidebar-subheading">
                      {{$valor->valor}}
                      <input type="checkbox" class="item pull-left" name="ano" data-value="anomod" value="{{$valor->valor}}" > 
                    </label>
                  </div>
                  @endforeach

                </div>
              </div>
            </div>

          @endforeach  --}}

          @if (isset($filtro_ano))

          @php
            $ano2 = array();
            if (isset($_GET["anomod"])) {
              foreach (explode(',',$_GET["anomod"]) as $a) {
                $ano2[] = $a;
              }
            }
          @endphp


          <div class="form-group">  
            <p><a href="" class="retrai" data-value="ano"><i class="fa fa-minus-square-o" data-value="ano"></i> Year</a> <span class="pull-right"><input type="checkbox" class="all" data-value="anomod"><small>All</small></span></p>            
            <div id="ano">
              <div class="row">
                @foreach ($filtro_ano as $ano)
                <div class="col-md-6">
                  @if (in_array($ano->anomod, $ano2))
                  <label class="control-sidebar-subheading">
                    {{$ano->anomod}}
                    <input type="checkbox" class="item pull-left" name="ano" data-value="anomod" value="{{$ano->anomod}}" checked> 
                  </label>
                  @else 
                  <label class="control-sidebar-subheading">
                    {{$ano->anomod}}
                    <input type="checkbox" class="item pull-left" name="ano" data-value="anomod" value="{{$ano->anomod}}" > 
                  </label>
                  @endif
                </div>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          @if (isset($filtro_colecao))


          @php
            $col2 = array();
            if (isset($_GET["colmod"])) {
              foreach (explode(',',$_GET["colmod"]) as $a) {
                $col2[] = $a;
              }
            }
          @endphp

          <div class="form-group">
            <p><a href="" class="retrai" data-value="colecao"><i class="fa fa-minus-square-o" data-value="colecao"></i> Collection</a> <span class="pull-right"><input type="checkbox" class="all" data-value="colecao"><small>All</small></span></p>
            <div id="colecao">
            <div class="row">
              @foreach ($filtro_colecao as $colecao)
              <div class="col-md-6">
                <label class="control-sidebar-subheading">
                  {{$colecao->colmod}}
                  <input type="checkbox" class="item pull-left" name="colecao" data-value="colecao" checked value="{{$colecao->colmod}}"> 
                </label>
              </div>
              @endforeach
            </div>
            </div>
          </div>
          @endif

          @if (isset($filtro_genero))

          @php
            $gen2 = array();
            if (isset($_GET["genero"])) {
              foreach (explode(',',$_GET["genero"]) as $a) {
                $gen2[] = $a;
              }
            }
          @endphp

          <div class="form-group">
            <p><a href="" class="retrai" data-value="genero"><i class="fa fa-minus-square-o" data-value="genero"></i> Genêro </a> <span class="pull-right"><input type="checkbox" class="all" data-value="genero"><small>All</small></span></p>
            <div id="genero">
            @foreach ($filtro_genero as $genero)
            <label class="control-sidebar-subheading">
              {{$genero->genero}}
              <input type="checkbox" class="item pull-left" data-value="genero" name="genero" value="{{$genero->genero}}" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif

          @if (isset($filtro_material))
          <div class="form-group">
            <p><a href="" class="retrai" data-value="material"><i class="fa fa-minus-square-o" data-value="material"></i> Material</a> <span class="pull-right"><input type="checkbox" class="all" data-value="material"><small>All</small></span></p>
            <div id="material">
            <div class="row">
              @foreach ($filtro_material as $material)
              <div class="col-md-6">
                <label class="control-sidebar-subheading">
                  {{$material->material}}
                  <input type="checkbox" class="item pull-left" name="material" data-value="material" value="{{$material->material}}" checked> 
                </label>
              </div>
              @endforeach
            </div>
            </div>
          </div>
          @endif

          @if (isset($filtro_idade))
          <div class="form-group">
            <p><a href="" class="retrai" data-value="idade"><i class="fa fa-minus-square-o" data-value="idade"></i> Age </a> <span class="pull-right"><input type="checkbox" class="all" data-value="idade"><small>All</small></span></p>
            <div id="idade">
            @foreach ($filtro_idade as $idade)
            <label class="control-sidebar-subheading">
              {{$idade->idade}}
              <input type="checkbox" class="item pull-left" name="idade" value="{{$idade->idade}}" data-value="idade" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif

          @if (isset($filtro_clas))
          <div class="form-group">
            <p><a href="" class="retrai" data-value="classificacao"><i class="fa fa-minus-square-o" data-value="classificacao"></i> Classification </a>
              <span class="pull-right"><input type="checkbox" class="all" data-value="classificacao"><small>All</small></span>
            </p>
            <div id="classificacao">
            @foreach ($filtro_clas as $clas)
            <label class="control-sidebar-subheading">
              {{$clas->clasmod}}
              <input type="checkbox" class="item pull-left" name="classificacao" value="{{$clas->codclasmod}}" data-value="classificacao" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif

          @if (isset($filtro_fixacao))
          <div class="form-group">
            <p><a href="" class="retrai" data-value="fixacao">
              <i class="fa fa-minus-square-o" data-value="fixacao"></i> Fixação </a>
              <span class="pull-right"><input type="checkbox" class="all" data-value="fixacao"><small>All</small></span>
            </p>
            <div id="fixacao">
            @foreach ($filtro_fixacao as $fixacao)
            <label class="control-sidebar-subheading">
              {{$fixacao->fixacao}}
              <input type="checkbox" class="item pull-left" data-value="fixacao"  name="fixacao" value="{{$fixacao->fixacao}}" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif

          

           
			@if(\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1)
          @if (isset($filtro_fornecedores))
          <div class="form-group">
            <p><a href="" class="retrai" data-value="fornecedor">
              <i class="fa fa-minus-square-o" data-value="fornecedor"></i> Factory </a>
              <span class="pull-right"><input type="checkbox" class="all" data-value="fornecedor"><small>All</small></span>
            </p>
            <div id="fornecedor">
            @foreach ($filtro_fornecedores as $fornecedor)
            <label class="control-sidebar-subheading">
              {{$fornecedor->fornecedor}}
              <input type="checkbox" class="item pull-left" name="fornecedor" value="{{$fornecedor->fornecedor}}"  data-value="fornecedor" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif
		  @endif

          @if (isset($filtro_status))
          <div class="form-group">
            <p>
              <a href="" class="retrai" data-value="status"><i class="fa fa-minus-square-o" data-value="status"></i> Status </a>
              <span class="pull-right"><input type="checkbox" checked="" class="all" data-value="status"><small>All</small></span>
            </p>
            <div id="status">
            @foreach ($filtro_status as $status)
            <label class="control-sidebar-subheading">
              {{$status->codstatusatual}}
              <input type="checkbox" class="item  pull-left" name="status" value="{{$status->codstatusatual}}" data-value="status" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif

          <div class="form-group">
            <p>
              <a href="" class="retrai" data-value="status"><i class="fa fa-minus-square-o" data-value="preco"></i> Preço </a>
            </p>
            <div id="preco">
              <div class="row">
                <div class="col-md-6">
                  <input type="text" name="preco_de" class="form-control" value="0" placeholder="de">
                </div>
                <div class="col-md-6">
                  <input type="text" name="preco_ate" class="form-control" value="100000" placeholder="ate">
                </div>
              </div>
            </div>
          </div>

{{-- 
          @if (isset($filtro_colecao))
          <div class="form-group">
            <p>
              <a href="" class="retrai" data-value="status"><i class="fa fa-minus-square-o" data-value="status"></i> Ciclo Coleção </a>
              <span class="pull-right"><input type="checkbox" class="all" data-value="status"><small>Todos</small></span>
            </p>
            <div id="status">
            @foreach ($filtro_colecao as $ciclo)
            <label class="control-sidebar-subheading">
              {{$ciclo->colmod}}
              <input type="checkbox" class="item  pull-left" name="status" value="{{$ciclo->colmod}}" data-value="status" checked> 
            </label>
            @endforeach
            </div>
          </div>
          @endif
 --}}

          <!-- /.form-group -->
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="control-sidebar-home-tab">
          <h3 class="control-sidebar-heading">Order By <button type="submit" id="aplicarFiltro" class="btn btn-sm btn-default pull-right">Apply</button></h3>

          <!--<input type="text" name="ano" class="form-control" placeholder="Search" id="ano">-->
          <div class="row">
            <div class="col-md-12">

              @php
                if (isset($_GET["ordem"])) {
                  $split = explode(',', $_GET["ordem"]);
                  $ordem = $split[0];
                } else {
                  $ordem = '';
                }
              @endphp

              <select name="ordem" id="ordem" class="form-control">
				  
				  @if(\Auth::user()->id_perfil <> 4)
				 
                <optgroup label="Vendas">
                  <option value="vda30dd" @if ($ordem == "vda30dd") echo selected @endif>Sales 30d</option>
				  <option value="vda60dd" @if ($ordem == "vda60dd") echo selected @endif>Sales 60d</option>
                  <option value="a_180dd" @if ($ordem == "a_180dd") echo selected @endif>Sales 180d</option>
                  <option value="vendas" @if ($ordem == "vendas") echo selected @endif>Sales Total</option>
				  <option value="orcamentos_valido" @if ($ordem == "orcamentos_valido") echo selected @endif>Orçamentos válidos</option>
                </optgroup>
                <optgroup label="Estoque">
                  <option value="brasil" @if ($ordem == "brasil") echo selected @endif> Brazil</option>
					<option value="cet" @if ($ordem == "cet") echo selected @endif> CET</option>
					<option value="cep" @if ($ordem == "cep") echo selected @endif> CEP</option>
					<option value="totaletq" @if ($ordem == "totaletq") echo selected @endif> Total</option>
								
                  
                  </optgroup>
                <optgroup label="Preço">
                  <option value="custo_2019" @if ($ordem == "custo") echo selected @endif>Cost</option>
                  <option value="valor" @if ($ordem == "valor") echo selected @endif>Price</option>              
                </optgroup>
				  
				  @endif
				  
                <optgroup label="Caracteristicas">
                  <option value="clasmod" @if ($ordem == "clasmod") echo selected @endif >Classification</option>
                  <option value="colmod" @if ($ordem == "colmod") echo selected @endif>Collection</option>              
                  <option value="modelo" @if ($ordem == "modelo") echo selected @endif>Model</option>              
                  <option value="estilo" @if ($ordem == "estilo") echo selected @endif>Style</option> 
					<option value="fixacao" @if ($ordem == "fixacao") echo selected @endif>Fixação</option> 
					<option value="idade" @if ($ordem == "idade") echo selected @endif>Idade</option> 
					<option value="tamolho" @if ($ordem == "tamolho") echo selected @endif>Tam Olho</option> 
                  @if(\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11)
					<option value="fornecedor" @if ($ordem == "fornecedor") echo selected @endif>Factory</option>
					@endif
                </optgroup>
              </select>

            </div>
          </div>

          <br>

          <div class="row">
            <div class="col-md-12">

              <select class="form-control" name="tipoOrdem" id="tipoOrdem">
                <option> ASC</option>
                <option> DESC</option>
              </select>

            </div>
          </div>

      </div>      
    </div>

  </aside>    
  </form>
