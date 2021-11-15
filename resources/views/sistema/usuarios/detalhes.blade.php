@extends('layout.principal')

@section('title')
<i class="fa fa-user"></i> Cadastro de Usuário
@append 

@section('conteudo')

@php


@endphp


@if (Session::has("alert-success"))
  <div class="callout callout-success">{{Session::get('alert-success')}}</div>
@endif

<form action="/usuarios/grava" method="post" class="form-horizontal">
@csrf
<div class="row">
  <div class="col-md-9">

    @if (Session::has('alert'))
      <div class="callout callout-warning text-bold">{{Session::get('alert')}}</div> 
    @endif

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o"></i> Dados do Usuário</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-2 control-label">Perfil</label>
          <div class="col-md-4">
            <select name="id_perfil" class="form-control">
              @foreach ($perfis as $perfil)
                <option value="{{$perfil->id}}" @if (isset($usuario->id) && $perfil->id == $usuario->id_perfil) selected @endif>{{$perfil->descricao}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-4">
            <input type="checkbox" name="admin" value="1" @if ($usuario->admin == 1) checked="" @endif> Administrador
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">ID AddressBook</label>
          <div class="col-md-8">
            <input type="text" name="id_addressbook" class="form-control" value="{{ $usuario->id_addressbook  }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Nome</label>
          <div class="col-md-8">
            <input type="text" name="nome" class="form-control" value="{{ $usuario->nome  }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">E-Mail</label>
          <div class="col-md-8">
            <input type="email" name="email" class="form-control" value="{{ $usuario->email  }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Senha</label>
          <div class="col-md-4">
            <input type="password" name="senha" @if ($usuario->id) disabled="" @endif class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-4">
            <input type="checkbox" name="reset" value="1"> Reset senha
          </div>
        </div>

        <a href="/login/trocar/{{$usuario->id}}"> Logar como este usuário </a></br>
              <div >
          <div class="box-header with-border">
            <h3 class="box-title"><i ></i> Em processo</h3>
          </div>
          <div class="box-body">

            <table class="table table-condensed table-bordered" >
          <thead>
            <tr>
              
              <th>Ação</th>
              <th>Grife</th>
              <th>Dt iniciada</th>
              <th>Dt alteração</th>
              <th>Destino/Origem</th>
              <th>Obs</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            
           
              <tr>
                <td> Recebimento de grife</td>
                <td> Atitude</td>
                <td> 10/05/2020</td>
                <td> 10/06/2020</td>
                <td> Show room</td>
                <td> Aguardando conferência rep</td>
                <td> Em processo</td>

              </tr>

          
          </tbody>
          </table>

          </div>
      </div>

      </div>

    </div>


@if ($usuario->id <> '')
    <div class="row">

      <div class="col-md-4">
        <div class="box box-widget">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-check-square-o"></i> Acessos</h3>
          </div>
          <div class="box-body">
            <ul class="list-unstyled"> 
            @php
              $menus = \App\Menu::listaMenus();
            @endphp

            @foreach ($menus as $menu)
              @php
                $acessos_usuarios = \DB::select("select * from acessos where tabela = 'usuarios' and id_tabela = $usuario->id and rota = '$menu->link'");
                $acessos_perfil = \DB::select("select * from acessos where tabela = 'perfis' and id_tabela = $usuario->id_perfil and rota = '$menu->link'");
              @endphp


              <li><input type="checkbox" @if ($acessos_usuarios) checked="" @else @if ($acessos_perfil) checked="" @else  name="acesso[]" value="{{$menu->id}}" @endif @endif> {{$menu->descricao}}</li>

              @foreach ($menu->submenu as $item)

                @php
                  $acessos_usuarios2 = \DB::select("select * from acessos where tabela = 'usuarios' and id_tabela = $usuario->id and rota = '$item->link'");
                  $acessos_perfil2 = \DB::select("select * from acessos where tabela = 'perfis' and id_tabela = $usuario->id_perfil and rota = '$item->link'");
                @endphp

                @if ($acessos_usuarios2)
                  <li style="padding-left: 20px;">
                    <input type="checkbox"  checked="" name="acesso[]" value="{{$item->id}}"> {{$item->descricao}} 
                  </li>
                @else 
                  @if ($acessos_perfil2) 
                    <li style="padding-left: 20px;">
                      <input type="checkbox"  checked="" disabled=""> {{$item->descricao}}   <i><small>(Perfil)</small></i>
                    </li>
                  @else
                    <li style="padding-left: 20px;">
                      <input type="checkbox"  name="acesso[]" value="{{$item->id}}"> {{$item->descricao}} 
                    </li>
                  @endif
                @endif

              @endforeach
            @endforeach
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-8">
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
                          @if (\App\PermissaoPerfil::verificaPermissao($usuario->id_perfil, 'grifes', $grife->codgrife))
                          <li class="text-bold"><i><input type="checkbox" name="grifes[]" value="{{$grife->codgrife}}"  checked="" > {{$grife->grife}} <small>(Perfil)</small></i></li>
                          @else
                          <li><input type="checkbox" name="grifes[]" value="{{$grife->codgrife}}" @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'grifes', $grife->codgrife)) checked @endif > {{$grife->grife}}<br></li>
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
                      <ul class="list-unstyled"> 
                        @foreach ($anos as $ano)
                        

                        <div class="col-md-4">
                          <input type="checkbox" class="selAll" data-value="{{$ano->anomod}}"> <b>{{$ano->anomod}}</b><br>

                          <ul class="list-unstyled"> 
                            @foreach ($ano->colecoesAno($ano->anomod) as $colecao)
                              @if (\App\PermissaoPerfil::verificaPermissao($usuario->id_perfil, 'colecoes', $colecao->colmod))

                                <li style="padding-left: 30px"><input type="checkbox" name="colecoes[]" value="{{$colecao->colmod}}" checked="" class="sel" data-value="{{$ano->anomod}}">  {{$colecao->colmod}}</li>
                            
                              @else
                                <li style="padding-left: 30px"><input type="checkbox" name="colecoes[]" value="{{$colecao->colmod}}" class="sel" data-value="{{$ano->anomod}}" @if (\App\PermissaoPerfil::verificaPermissao($perfil->id, 'colecoes', $colecao->colmod)) checked="" @endif>  {{$colecao->colmod}}</li>


                              @endif                 
                            @endforeach 
                          </ul>
                        </div>
                       

                        @endforeach
                     </ul>

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
                            @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'filtros', $filtro->campo))

                                <li><input type="checkbox" name="filtros[]" value="{{$filtro->campo}}"  checked="" > {{$filtro->campo}}</li>

                            @else 
                              @if (\App\PermissaoPerfil::verificaPermissao($usuario->id_perfil, 'filtros', $filtro->campo))

                                <li><input type="checkbox" name="filtros[]" value="{{$filtro->campo}}"  checked="" disabled="" > {{$filtro->campo}} <i><small>(Perfil)</small></i></li>

                              @else 

                                <li><input type="checkbox" name="filtros[]" value="{{$filtro->campo}}"  > {{$filtro->campo}}</li>

                              @endif
                            @endif
                          </div>
                        @endforeach 
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel box box-warning">
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
                            <li><input type="checkbox" name="vendas" value="1"  @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'vendas', 1)) checked @endif> Vendas</li>
                          </div>
                          <div class="col-md-6">
                            <li><input type="checkbox" name="estoques" value="1"  @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'estoques', 1)) checked @endif> Estoques</li>
                          </div>

                          <div class="col-md-6">
                            <li><input type="checkbox" name="vendas" value="1"  @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'tabela', 1)) checked @endif> Preço Tabela</li>
                          </div>
                          <div class="col-md-6">
                            <li><input type="checkbox" name="vendas" value="1"  @if (\App\PermissaoUsuario::verificaPermissao($usuario->id, 'custo', 1)) checked @endif> Preço Custo</li>
                          </div>                          
                        </div>
                     </ul>                      

                    </div>
                  </div>
                </div>
              </div>



          </div>
        </div>

      </div>
    </div>
@endif

    
  </div>
  <div class="col-md-3">
    <div class="box box-widget">
     <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-4 control-label">ID</label>
          <div class="col-md-7">
            <input type="text" name="id_usuario" readonly value="{{$usuario->id}}" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Status</label>
          <div class="col-md-7">
            <select name="status" class="form-control">
              <option value="1" @if ($usuario->status == 1) selected @endif>Ativo</option>
              <option value="0" @if ($usuario->status == 0) selected @endif>Inativo</option>
            </select>
          </div>
        </div>

        <div class="box-footer" align="center">
          <button class="btn btn-flat btn-default">Gravar</button>
        </div>

      </div>
    </div>
  </div> 
</div>
</form>

@stop
