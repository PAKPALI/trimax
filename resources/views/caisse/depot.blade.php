@extends('layouts.layout')
@section('content')


<div class="clearfix hidden-md-up"></div>
<div class="col-12 col-sm-12 col-md-12 mt-5">
    <div class="info-box mb-3">
        @if($somme>0)
        <span class="info-box-icon bg-success elevation-1">FCFA</span>
        @else
        <span class="info-box-icon bg-danger elevation-1">FCFA</span>
        @endif
        <div class="info-box-content">
            <span class="info-box-text">
                <h1>Somme Totale</h1>
            </span>
            <span class="info-box-number">
                <h2>{{$somme}}</h2>
            </span>
        </div>
    </div>
</div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>DEPOT</h1>
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

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="depot">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputText0">Banque</label>
                                <input type="text" name="banque" class="form-control" id="exampleInputText0"
                                    placeholder="Enter le nom de la banque">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText1">Somme</label>
                                <input type="number" name="somme" class="form-control" id="exampleInputText1"
                                    placeholder="Enter la somme">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText2">Confirmer Somme</label>
                                <input type="number" name="confirmersomme" class="form-control" id="exampleInputText2"
                                    placeholder="Confirmer la somme">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText3">Description</label>
                                <textarea name="desc" class="form-control" id="exampleInputText3"
                                    placeholder="Enter la description"></textarea>
                            </div>
                            <div id="loader" class="text-center">
                                <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"
                                    height="70" width="70">
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
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-primary">
                        <h2 class="card-title">LISTE DES OPERATIONS DE DEPOT</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Somme</th>
                                    <th>Banque</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 0
                                @endphp

                                @foreach($Operation as $op)
                                    <tr>
                                        <td>{{$op->id}}</td>
                                        <td class="text-primary">+{{$op->somme}}</td>
                                        <td>{{$op->banque}}</td>
                                        <td>{{$op->desc}}</td>
                                        <td>{{$op->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-md-6">
                </div>

            </div>

        </div>
</section>

<script>
$('#loader').hide();
$(function() {
    $('#loader').hide();
    //Ajax pour ajouter depot
    $('#depot').submit(function() {
        event.preventDefault();
        $('#loader').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'depot',
            //enctype: 'multipart/form-data',
            data: $('#depot').serialize(),
            datatype: 'json',
            success: function(data) {
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
    });
    return false;
});
</script>

</div>
@endsection