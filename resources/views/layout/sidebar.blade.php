  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
@if  ( \Auth::user()->id_perfil <> 23)
      <!-- search form -->
      <form action="/painel/search/" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="busca" class="form-control" placeholder="Item ou pedido de compra">
          <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>

      
	@endif
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>

        @php


          $menus = \App\Menu::listaMenus();

        @endphp
        

        @foreach ($menus as $menu)
            @if (\App\Acesso::verificaAcesso($menu->link))
              @php
                $submenus = \App\Menu::listaSubMenus($menu->id);
              @endphp

              @if (isset($submenus) && count($submenus) > 0) 

                <li class="treeview">
                  <a href="#">
                    <i class="fa {{$menu->icone}}"></i> <span>{{$menu->descricao}}</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">

                    @foreach ($submenus as $submenu)
                      @if (\App\Acesso::verificaAcesso($submenu->link))

                        @php

                          $submenus2 = \DB::select("select * from menus where id_menu_pai = $submenu->id");

                        @endphp

                        @if ($submenus2) 


                          <li class="treeview">
                            <a href="#">
                              <i class="fa {{$submenu->icone}}"></i> <span>{{$submenu->descricao}}</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu">
                              @foreach ($submenus2 as $submenu2)
                                <li><a href="{{$submenu2->link}}"> {{$submenu2->descricao}}</a></li>
                              @endforeach 
                            </ul>
                          </li>


                        @else
                            <li><a href="{{$submenu->link}}"><i class="fa {{$submenu->icone}}"></i> {{$submenu->descricao}}</a></li>
                        @endif
                      @endif
                    @endforeach

                  </ul>
                </li>

              @else 
		  
		  		@php
		  			$id_perfil = \Auth::user()->id_perfil;
		  			$perfil = \DB::select("select * from perfis where id = $id_perfil");
		  
		  
		  		@endphp
		  
		  		@if ($menu->descricao == 'Dashboard' && isset($perfil) && $perfil[0]->home <> '' )
              <li>
                <a href="{{$perfil[0]->home}}">
                  <i class="fa {{$menu->icone}}"></i> <span>{{$menu->descricao}}</span>
                </a>
              </li>		  
		  		@else
              <li>
                <a href="/{{$menu->link}}">
                  <i class="fa {{$menu->icone}}"></i> <span>{{$menu->descricao}}</span>
                </a>
              </li>		  
		  
		  		@endif

              @endif


            @endif
        @endforeach

        <li>
          <a href="/exemplo">
            <i class="fa fa-circle"></i> <span>Exemplo</span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>