<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KCP Persediaan | Log In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }} ">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>KCP</b>Persediaan</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                @if ($message = Session::get('success'))
                <div class="alert alert-success mt-3">
                <div class="row">
                    <i class="fas fa-info-circle p-1"></i><p>{{ $message }}</p>            
                </div>
                </div>
                @elseif ($message = Session::get('danger'))
                <div class="alert alert-danger mt-3">
                <div class="row">
                    <i class="fas fa-info-circle p-1"></i><p>{{ $message }}</p>           
                </div>

                </div>
                @endif

            <form action="{{ route('login.auth') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
                <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" id="username" placeholder="username">
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                    </div>
                </div>
                </div>
                <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="password">
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
                </div>
                <div class="row">

                <!-- /.col -->
                <div class="col-4">
                    <div class="button justify-content-center">
                    <button type="submit" class="btn btn-primary mx-auto d-block mt-2" style="width: 300px">Masuk</button>
                    </div>
                </div>
                <!-- /.col -->
                </div>
            </form>

            </div>
        </div>
    </div>

<!-- jQuery -->
<script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/plugins/bootstrap/js/bootstrap.bundle.min.js')}} "></script>
<!-- AdminLTE App -->
<script src="{{ asset('/dist/js/adminlte.min.js')}} "></script>
</body>
</html>