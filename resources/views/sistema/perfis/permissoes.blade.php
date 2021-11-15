  <div class="box box-widget">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-eye"></i> Permissões</h3>
    </div>
    <div class="box-body">



      <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                Grifes
              </a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse">
            <div class="box-body">
              <ul class="list-unstyled"> 
                <div class="row">
                  @foreach ($grifes as $grife)

                  <div class="col-md-6">
                    @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'grifes', $grife->codgrife))
                    <li class="text-bold"><i><input type="checkbox" name="grifes[]" value="{{$grife->codgrife}}" checked="" > {{$grife->grife}} <small>(Perfil)</small></i></li>
                    @else
                    <li><input type="checkbox" name="grifes[]" value="{{$grife->codgrife}}" @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'grifes', $grife->codgrife)) checked @endif > {{$grife->grife}}<br></li>
                    @endif
                  </div>

                  @endforeach
                </div>
              </ul>
            </div>
          </div>
        </div>
        <div class="panel box box-danger">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                Coleções
              </a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <div class="box-body">
              <div class="row"> 
                @foreach ($anos as $ano)
                

                <div class="col-md-4">
                  <input type="checkbox" class="selAll" data-value="{{$ano->anomod}}"> <b>{{$ano->anomod}}</b><br>

                  <ul class="list-unstyled"> 
                    @foreach ($ano->colecoesAno($ano->anomod) as $colecao)
                      <li style="padding-left: 30px"><input type="checkbox" name="colecoes[]" value="{{$colecao->colmod}}" class="sel" data-value="{{$ano->anomod}}" @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'colecoes', $colecao->colmod)) checked="" @endif>  <i class='fa fa-cart-plus'></i> <i class='fa fa-suitcase'></i>
                      {{$colecao->colmod}}</li>
                     
                    @endforeach 
                  </ul>
                </div>
               

                @endforeach
              </div>

            </div>
          </div>
        </div>



        @php
          $filtros = \DB::select("select campo from caracteristicas group by campo");
        @endphp

        <div class="panel box box-success">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                Filtros
              </a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse">
            <div class="box-body">

              <div  class="row">
                <ul class="list-unstyled"> 

                @foreach ($filtros as $filtro)
                  <div class="col-md-6">
                      @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'filtros', $filtro->campo))

                        <li><input type="checkbox" name="filtros[]" value="{{$filtro->campo}}"  checked="" > {{$filtro->campo}}</li>

                      @else 

                        <li><input type="checkbox" name="filtros[]" value="{{$filtro->campo}}"  > {{$filtro->campo}}</li>

                      @endif
                  </div>
                @endforeach 
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="panel box box-success">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                Outros
              </a>
            </h4>
          </div>
          <div id="collapseFour" class="panel-collapse collapse">
            <div class="box-body">

             <ul class="list-unstyled"> 
              <div class="row">

                <div class="col-md-6">
                  <li><input type="checkbox" name="vendas" value="1"  @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'vendas', 1)) checked @endif> Vendas</li>
                </div>
                <div class="col-md-6">
                  <li><input type="checkbox" name="estoques" value="1"  @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'estoques', 1)) checked @endif> Estoques</li>
                </div>

                <div class="col-md-6">
                  <li><input type="checkbox" name="preco_venda" value="1"  @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'valor', 1)) checked @endif> Preço Tabela</li>
                </div>
                <div class="col-md-6">
                  <li><input type="checkbox" name="preco_custo" value="1"  @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'custo', 1)) checked @endif> Preço Custo</li>
                </div>                          
              </div>
            </ul>                      

          </div>
        </div>
      </div>
    </div>



  </div>
