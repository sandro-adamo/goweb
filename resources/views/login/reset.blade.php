
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Portal GO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <!-- iCheck 
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">-->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
{{--     <center><a href=""><img src="/img/logogo.png" class="img-responsive" width="100"></a></center> --}}
<br>
  </div>
  <!-- /.login-logo -->

  @if (Session::has("alert-warning"))
    <div class="callout callout-warning">{{Session::get("alert-warning")}}</div>
  @endif
  <div class="login-box-body">
    <p class="login-box-msg"><span class="lead">Alterar Senha</span></p>

    @if (Session::has('alert'))
      <p align="center" class="text-bold text-red">{{Session::get('alert')}}</p> 
    @endif

    <form action="/reset" method="post">
      @csrf
      <div class="form-group has-feedback">
        <input type="password" class="form-control input-lg" required="" name="senha" placeholder="nova senha" autofocus="">
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control input-lg" required="" name="confirma" placeholder="confirma senha">       
      </div>

      <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat btn-lg">Alterar</button>
        </div>
      </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
