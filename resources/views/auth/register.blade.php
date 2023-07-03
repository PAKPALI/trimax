@extends('auth.layouts.layoutAuth')
@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="../../index2.html"><b>Bienvenue </b>Admin</a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">S'enregistrer comme Admin</p>
            <form id="add">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="nom" class="form-control" placeholder="Nom complet">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmez mot de passe">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <!-- loader -->
                <div id="loader" class="text-center">
                    <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"
                                    height="70" width="70">
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">S'enregistrer</button>
                    </div>

                </div>
            </form>
            <!-- <a href="" class="text-center">Bienvenue notre admin</a> -->
        </div>

    </div>
</div>

<script>
    $('#loader').hide();
    $(function() {
        $('#loader').hide();
        //Ajax pour ajouter user
        $('#add').submit(function() {
            event.preventDefault();
            $('#loader').fadeIn();
            $.ajax({
                type: 'POST',
                url: "{{route('ajouter_admin')}}",
                //enctype: 'multipart/form-data',
                data: $('#add').serialize(),
                datatype: 'json',
                success: function(data) {
                    $('#loader').hide();
                    console.log(data)
                    if (data.status) {
                        Swal.fire({
                            icon: "success",
                            title: data.title,
                            text: data.msg,
                        }).then(() => {
                            if (data.redirect_to != null) {
                                window.location.assign(data.redirect_to)
                            } else {
                                window.location.reload()
                            }
                        })
                    } else {
                        $('#loader').hide();
                        Swal.fire({
                            title: data.title,
                            text: data.msg,
                            icon: 'error',
                            confirmButtonText: "D'accord",
                            confirmButtonColor: '#A40000',
                        })
                    }
                },
                error: function(data) {
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