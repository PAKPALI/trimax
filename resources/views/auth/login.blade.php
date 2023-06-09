@extends('auth.layouts.layoutAuth')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>TRIMAX</b>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body text-center">
                <img class="animation__shake " src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo" height="100" width="100">
                <p class="login-box-msg mt-3 mb-3">SE CONNECTER</p>

                <form id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <!-- <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div> -->
                        <!-- /.col -->
                        <div class="col mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Connecter</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> -->
                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <!-- <a href="register.html" class="text-center">Register a new membership</a> -->
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>

    <script>
        $(function() {
            $('#loader').hide();

            //ajax pour se connecter
            $('#form-login').submit(function(){
                event.preventDefault();
                $('#loader').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: 'login',
                    //enctype: 'multipart/form-data',
                    data: $('#form-login').serialize(),
                    datatype: 'json',
                    success: function (data){
                        console.log(data)
                        if (data.status)
                        {
                            Swal.fire({
                                icon: "success",
                                title: data.title,
                                text: "Connection reussie!",
                            }).then(() => {
                                if (data.redirect_to != null){
                                    window.location.assign(data.redirect_to)
                                } else{
                                }
                            })
                        }else{
                            $('#loader').hide();
                            Swal.fire({
                                title: data.title,
                                text:data.msg,
                                icon: 'error',
                                confirmButtonText: "D'accord",
                                confirmButtonColor: 'blue',
                            })
                        }
                    },
                    error: function (data){
                        console.log(data)
                        $('#loader').hide();
                        Swal.fire({
                            icon: "error",
                            title: "erreur",
                            text: "Impossible de communiquer avec le serveur.",
                            timer: 3600,
                        })
                    }
                });
                return false;
            });
        });
    </script>
@endsection