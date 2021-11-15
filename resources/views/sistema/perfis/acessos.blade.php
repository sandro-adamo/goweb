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
        <li><input type="checkbox" name="acessos[]" value="{{$menu->link}}" @if (\App\Acesso::verificaAcessoPerfil($perfil->id, $menu->link)) checked @endif> {{$menu->descricao}}</li>

        @foreach ($menu->submenu as $item)
          
          <li style="padding-left: 20px;"><input type="checkbox" name="acessos[]" value="{{$item->link}}" @if (\App\Acesso::verificaAcessoPerfil($perfil->id, $item->link)) checked @endif> {{$item->descricao}}</li>

        @endforeach
      @endforeach
      </ul>
    </div>
  </div>
