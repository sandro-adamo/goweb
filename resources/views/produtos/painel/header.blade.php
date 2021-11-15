
  <header class="main-header">
    <nav class="navbar navbar-static-top" style="background: #23364A !important;">
<!--      <div class="container">-->
        <div class="navbar-header" style="background: #23364A !important;">
          <a href="/painel" class="navbar-brand"><b><i class="fa fa-object-group"></i> Painel</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="background: #23364A !important;">
          <ul class="nav navbar-nav">
            <li class=""><a href="/"><i class="fa fa-home"></i> <span class="sr-only">(current)</span></a></li>
            <li><a href="/painel">@lang('painel.painel')</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">@lang('painel.catalogo') <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/catalogo/novo">@lang('painel.novo_catalogo')</a></li>
                <li><a href="/meus-catalogos/">@lang('painel.meus_catalogo')</a></li>
                <li class="divider"></li>
                <li><a href="/catalogo/padrao/imediato">Imediato</a></li>
                <li><a href="/catalogo/padrao/lancamento">Lançamentos</a></li>
                <li><a href="/catalogo/padrao/linha">Linha</a></li>
                <li><a href="/catalogo/padrao/essenciais">Essenciais</a></li>
                <li><a href="/catalogo/padrao/oportunidade">Oportunidade</a></li>
                <li><a href="/catalogo/padrao/top10">TOP 10</a></li>
				<li><a href="/catalogo/padrao/musthave">Must Have</a></li>
				<li><a href="/catalogo/padrao/Pro">Pro</a></li>
				<li><a href="/catalogo/padrao/prime_lancamento">Prime Lançamento</a></li>
				<li><a href="/catalogo/padrao/prime_imediato">Prime Imediato</a></li>
				
				
				 @if (Auth::user()->nome = 'JM REPRESENTACOES DE PRODS OPTICOS LTDA')
				<li><a href="/catalogo/pedido">Pedido Excel</a></li>
				  @endif
              </ul>
            </li>

            @if (Session::has('novocatalogo'))
              @php
                $novocatalogo = Session::get('novocatalogo');
              @endphp
              <li class="bg-green"><a href="/catalogo/{{$novocatalogo["codigo"]}}"><i class="fa fa-edit"></i> Editando: {{$novocatalogo["descricao"]}} <small>({{$novocatalogo["codigo"]}})</small></a></li>
            @endif


          </ul>
          <form class="navbar-form navbar-left" role="search" action="/painel/search/">
            <div class="form-group">
              <input type="text" class="form-control" name="busca" autofocus="" id="busca" id="navbar-search-input" placeholder="Search">
            </div>
          </form>


        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu" style="background: #23364A !important;">
          <ul class="nav navbar-nav" style="background: #23364A !important;">
            <!-- Control Sidebar Toggle Button -->

            @php
              $id_usuario = \Auth::id();
              $favoritos = \DB::select("select * from favoritos where id_usuario = $id_usuario");
            @endphp
            <li>
              @if ($favoritos && count($favoritos) > 0 ) 
                <a href="/painel/favoritos"><i class="fa fa-heart text-red" id="favoritos"></i> <span class="label label-success" id="qtdeFavoritos">{{count($favoritos)}}</span></a>
              @else 
                <a href="/painel/favoritos"><i class="fa fa-heart-o text-red" id="favoritos"></i> </a>
              @endif
            </li> 
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-filter"></i></a>
            </li>            
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      <!--</div>-->
      <!-- /.container-fluid -->
    </nav>
  </header>