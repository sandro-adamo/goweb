<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
    if (Session::has('novocatalogo')) {
      $novocatalogo = Session::get('novocatalogo'); 
      echo '<meta name="novocatalogo" content="'.$novocatalogo["codigo"].'" >';    
    }
  @endphp
  <title>@yield('titulo')</title>
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
  <link rel="shortcut icon" href="/img/logogo.png" >
  {{-- <link rel="stylesheet" href="/css/jquery-ui.min.css"> --}}
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

  <style>
    .box {
      border-radius: 0px;
    }
    .table2 td {
      padding:1px !important;  
    }
    .table {
      padding:0 !important;
      margin:0 !important;
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
  </style>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue-light layout-top-nav fixed">
  <div class="carregando"></div>  
<!-- Site wrapper -->
<div class="wrapper">
  @include('produtos.painel.header')

@php 
header('Content-Type: text/plain');
@endphp   

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
@php
$uri = Request::path();
//echo $uri;
@endphp
    <!-- Content Header (Page header) -->

{{--     <section class="content-header">
      <h1>
         @yield('title2')
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>
 --}}
    <!-- Main content -->
    <section class="content">

        @yield('conteudo')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include ('layout.footer')  

  @include ('produtos.painel.control-sidebar')  

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

  <!-- Modal -->
  <div class="modal fade" id="zoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-search"></i> Zoom</h4>
        </div>
        <div class="modal-body">
          <div id="imagem"></div>
        </div>
        <div class="modal-footer">
          <div id="status" style="font-size: 16px;" class="pull-left"></div>
          <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/js/jquery.min.js"></script>
{{-- <script src="/js/jquery-ui.min.js"></script> --}}
<!-- Bootstrap 3.3.7 -->
<script src="/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/js/filtros-catalogo.js"></script>
<script src="/js/produtos.js"></script>
<script src="/js/compras.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script>

$('.carousel').carousel({
    interval: false
}); 
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
  
  $(document).ready( function () {
      $(".carregando").fadeOut();

      $('#myTable').DataTable({
         paging: false,
         order: [[ 0, "desc" ]]
      });
  });


  $(".zoom").click(function(e) {
      e.preventDefault();
      var referencia = $(this).data('value');
      var status = $(this).data('status');

      $("#zoom #myModalLabel").html(referencia);
      $("#zoom #imagem").html('<img src="https://gestao.goeyewear.com.br/teste999_alta.php?referencia='+referencia+'" class="img-responsive" >');
      $("#zoom #status").html(status);
      $("#zoom").modal('show');
  });

  $(".cicloColecao").click(function(event) {
    var modelo = $(this).data('modelo');
    var colecao = $(this).data('colecao');
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
      url: '/api/modelos/ciclos',
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': token
      },
      data: {
        modelo: modelo,
        colecao: colecao
      },
      dataType: "json",
    })
    .done(function() {
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    

  }); 


  $(".btnAddItem").click(function(e) {
    e.preventDefault();
    var codigo = $('meta[name="novocatalogo"]').attr('content');
    $("#modalAddItem #codigo").val(codigo);
    $("#modalAddItem").modal('show');
  });

  $(".carrega").click(function(event) {
    /* Act on the event */
    $(".carregando").fadeIn();
  });
</script>
</body>
</html>