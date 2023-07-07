@extends('layouts.layout')
@section('content')


<div class="clearfix hidden-md-up"></div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>PROFIL</h1>
            </div>
            <div class="col-sm-6">
                <!-- <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">depot</li>
                </ol> -->
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="updatePassword">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input type="hidden" name="id" value="{{Auth::user()->id}}" class="form-control" id="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText1">Ancien mot de passe</label>
                                <input type="password" name="AM" class="form-control" id="somme"
                                    placeholder="Ancien de mot de passe">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText2">Nouveau mot de passe</label>
                                <input type="password" name="NM" class="form-control" id="c.somme"placeholder="Nouveau mot de passe">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText3">Confirmez mot de passe</label>
                                <input type="password" name="CM" class="form-control" id="exampleInputText3"placeholder="Confirmez le mot de passe">
                            </div>
                            <div id="loader" class="text-center">
                                <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"height="70" width="70">
                            </div>
                            <!-- <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1">
                                    <label class="custom-control-label" for="exampleCheck1">I agree to the <a
                                            href="#">terms of service</a>.</label>
                                </div>
                            </div> -->
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">Valider</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
</section>

<script>
$('#loader').hide();
$(function() {
    $('#loader').hide();
    //Ajax pour ajouter depot
    $('#updatePassword').submit(function(){ event.preventDefault();
        $('#load').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'updatePassword',
            //enctype: 'multipart/form-data',
            data: $('#updatePassword').serialize(),
            datatype: 'json',
            success: function (data){
                console.log(data)
                if (data.status)
                {
                    Swal.fire({
                        icon: "success",
                        title: data.title,
                        text: data.msg,
                    }).then(() => {
                        if (data.redirect_to == "1") {
                            window.location.assign(data.redirect_to)
                        } else {
                            window.location.reload()
                        }
                        //window.location.reload();
                    })
                }else{
                    Swal.fire({
                        title: data.title,
                        text:data.msg,
                        icon: 'error',
                        confirmButtonText: "D'accord",
                        confirmButtonColor: '#A40000',
                    })
                }
            },
            error: function (data){
                console.log(data)
                Swal.fire({
                    icon: "error",
                    title: "erreur",
                    text: "Impossible de communiquer avec le serveur.",
                    timer: 3600,
                })
            }
        });
        $('#load').hide();
    });
});
</script>

</div>
@endsection