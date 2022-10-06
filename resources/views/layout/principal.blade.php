@php
  //print_r($representantes);
  $sidebar_collapse = ' ';
  if (isset($_COOKIE["sw"])) {
    if ($_COOKIE["sw"] < 1028) {
      $sidebar_collapse = ' sidebar-collapse ';
    }
  }

  $id_usuario = \Auth::user()->id_addressbook;
  $id_perfil = \Auth::user()->id_perfil;
  //dd($_COOKIE["sw"]);
@endphp

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestão GO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="/css/jquery-ui.min.css">
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <link href="/c3-master/docs/css/c3.css" rel="stylesheet" type="text/css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/plugins/select2/dist/css/select2.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="shortcut icon" href="/img/logogo.png" >

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    .box {
      border-radius: 0px;
    }

    .carregando {
      background: url('/img/carregando.gif') center no-repeat #FFF; 
      position: fixed; 
      left: 0px; 
      top: 0px; 
      width: 100%; 
      height: 100%; 
      z-index: 9999; 
      opacity: 0.8;
    }  

@media (max-width: 1024px) {
  #sidebar {
    display: none;
  }
}

  </style>
  @yield('css')
</head>
<body class="hold-transition skin-red-light sidebar-mini {{$sidebar_collapse}} fixed">
  <div class="carregando"></div>  

<!-- Site wrapper -->
<div class="wrapper">
  @include('layout.header')

  @include('layout.sidebar')

  @php
   // $rota_atual = \Route::getFacadeRoot()->current()->uri();
  @endphp
  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		  <h1>@yield('icon') @yield('title')</h1>
<!--
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
-->
    </section>

    <!-- Main content -->
    <section class="content">

    {{-- if (\App\Acesso::verificaAcesso("/".$rota_atual)) --}}
        @yield('conteudo')
    {{-- endif --}}

      @php
// foreach (\Route::getRoutes()->getIterator() as $route){
//     if ($route->uri){
//         echo $route->uri.'<br>';
//     }
// }
      @endphp


    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->

  @include ('layout.footer')  
  @include ('layout.codigos')  


  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

  <!-- Modal -->
  <div class="modal fade" id="zoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Zoom</h4>
        </div>
        <div class="modal-body">
          <div id="imagem"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-orange">
          <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4> 
        </div>
        <div class="modal-body">
          <p class="lead">Prazo para envio de nota fiscal expirado ( até o dia 25 ).</p>
          <p class="lead"> Para desbloqueio financeiro, enviar NF pendente e NF vigente.</p>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Config</h4>
        </div>
        <div class="modal-body">
          @if (Session::has('representantes')) {{Session::get('representantes')}} @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>



</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/js/jquery.min.js"></script>
<!-- ChartJS -->
<script src="/js/chart.js/Chart.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes
<script src="/js/demo.js"></script> -->
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/produtos.js"></script>
<script src="/js/compras.js"></script>
<script src="/js/importacoes.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<!-- Select2 -->
<script src="/plugins/select2/dist/js/select2.full.min.js"></script>
<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
<script src="/c3-master/docs/js/c3.js"></script>
<script src="/js/dashboard.js"></script>
<script src="/js/ecommerce.js"></script>
<script src="/js/inventario.js"></script>
<script src="/js/clientes.js"></script>
<script src="/plugins/jquery-mask/jquery.mask.js"></script>

<script>
$(".btnCarregando").click(function(){
  
  $(".carregando").fadeIn();

});
    //$.cookie('sw',screen.width);
   // $.cookie('sh',screen.height);
if(window.event && window.event.keyCode == 113) {
  $("#config").modal('show');
}
$(document).ready(function(){
  $(".carregando").fadeOut();
document.cookie = "sw="+screen.width;
document.cookie = "sh="+screen.height;
  $('.date').mask('00/00/0000');
  $('.time').mask('00:00:00');
  $('.date_time').mask('00/00/0000 00:00:00');
  $('.cep').mask('00000-000');
  $('.phone').mask('0000-0000');
  $('.celular').mask('(00) 00000-0000');
  $('.phone_us').mask('(000) 000-0000');
  $('.mixed').mask('AAA 000-S0S');
  $('.cpf').mask('000.000.000-00', {reverse: true});
  $('.cnpj').mask('00.000.000/0000-00');
  $('.money').mask('000.000.000.000.000,00', {reverse: true});
  $('.money2').mask("#.##0,00", {reverse: true});
  $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
    translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      }
    }
  });
  $('.ip_address').mask('099.099.099.099');
  $('.percent').mask('##0,00%', {reverse: true});
  $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
  $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
  $('.fallback').mask("00r00r0000", {
      translation: {
        'r': {
          pattern: /[\/]/,
          fallback: '/'
        },
        placeholder: "__/__/____"
      }
    });
  $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
});



$(function () {
  $('#example1').DataTable();
  $('.tabela3').DataTable({
    'paging'      : false,
    'lengthChange': false,
    'searching'   : false,
    'ordering'    : true,
    'order'       : [[ 1, "desc" ]],
    'info'        : false,
    'autoWidth'   : false
  });
  $('#example3').DataTable({
    'paging'      : false,
    'lengthChange': false,
    'searching'   : false,
    'ordering'    : true,
    'order'       : [[ 1, "desc" ]],
    'info'        : false,
    'autoWidth'   : false
  });
  $('#example2').DataTable({
    'paging'      : false,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false
  });
  $('.tabela').DataTable({
    'paging'      : true,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : false,
    'info'        : true,
    'autoWidth'   : false
  });
  $('.tabela2').DataTable({
    'paging'      : false,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false
  });
});

$('.carousel').carousel({
    interval: false
}); 

$("#selSituacaoCliente1").change(function(event) {
    var valor = $("#selSituacaoCliente1 option:selected").val();

    if (valor == 'Visitando') {
      $("#motivos1").css('display', 'inline');
    } else {
      $("#motivos1").css('display', 'none');      
    }

});


$(".selSituacaoCliente2").change(function(event) {

    var grife = $(this).data('value');
    var valor = $("option:selected", this).val();


    if (valor == 'Visitando') {
      $("#modalDetalhes #grife").val(grife);
      $("#modalDetalhes #imgGrife").html('<img src="/img/marcas/'+grife+'.png" class="img-responsive" width="200" >');
      $("#modalDetalhes").modal('show');
    } 
});



// $(document).ready(function(){
//     if(navigator.geolocation){
//         navigator.geolocation.getCurrentPosition(showLocation);
//     }else{ 
//         $('#location').html('Geolocation is not supported by this browser.');
//     }
// });

// function showLocation(position){
//     var latitude = position.coords.latitude;
//     var longitude = position.coords.longitude;
//     console.log(latitude);


//     $.ajax({
//         type:'POST',
//         url:'getLocation.php',
//         data:'latitude='+latitude+'&longitude='+longitude,
//         success:function(msg){
//             if(msg){
//                $("#location").html(msg);
//             }else{
//                 $("#location").html('Not Available');
//             }
//         }
//     });
// }

$(document).ready( function () {
    $('#myTable').DataTable({
       paging: false,
       "order": [[ 0, "desc" ]]
    });
} );


  $(".uploadArquivo").click(function(event) {
    event.preventDefault();
    $("#modalUploadArquivo").modal('show');
    $("#job_id").val($(this).data('value'));
  });

  $(document).ready(function () {
    $('.sidebar-menu').tree();
  });

  $(".selAll").change(function(event) {
    var status = $(this).prop('checked');
    var campo = $(this).data('value');

    if (status == true) {

      $('.sel[data-value="'+campo+'"]').attr('checked', true);

    } else {

      $('.sel[data-value="'+campo+'"]').attr('checked', false);

    }

  });
  $(".seleAll").change(function(event) {
    var status = $(this).prop('checked');

    if (status == true) {

      $('.seleItem').attr('checked', true);

    } else {

      $('.seleItem').attr('checked', false);

    }

  });

  $("#seleAll").change(function(event) {
    var status = $(this).prop('checked');

    if (status == true) {

      $('.seleItem').attr('checked', true);

    } else {

      $('.seleItem').attr('checked', false);

    }

  });
  $(".zoom").click(function(e) {
      e.preventDefault();
      var referencia = $(this).data('value');

      $("#zoom #myModalLabel").html(referencia);
	  //$("#zoom #imagem").html('<img src="https://portal.goeyewear.com.br/fotoAlta.php?referencia='+referencia+'" class="img-responsive" >');
      $("#zoom #imagem").html('<img src="https://portal.goeyewear.com.br/teste999.php?referencia='+referencia+'" class="img-responsive" >');
      //$("#zoom #status").html(status);
      $("#zoom").modal('show');
  });


  $('.select2').select2();

function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
}


// $("#frmConfereMostruario").on('submit', function(e){
//   //e.preventDefault();

//   $.ajax({
//     url: '/mostruarios/devolucoes/checa',
//     data: {
//       'item': $("#referencia").val()
//     },
//     dataType: "json",
//     success: function(result) {

//       if (result[0].Situacao_Peca == 'DEVOLVER') {
//         beep();
//       }

//     }
//   })
//   //$(this).submit();
// });
</script>
  @yield('js')
</body>
</html>
