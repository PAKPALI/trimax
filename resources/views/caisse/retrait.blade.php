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
                <h1>SORTIE</h1>
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

                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="retrait">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Selectionnez sous caisse</label>
                                <select class="form-control select2" name="selection" style="width: 100%;">
                                    <option value="" selected="selected"></option>
                                    @foreach($SousCaisse as $sc)
                                        <option value="{{$sc -> id}}">{{strtoupper($sc -> nom)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText1">Somme</label>
                                <input type="text" name="somme" class="form-control" id="somme"
                                    placeholder="Enter la somme">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText2">Confirmer Somme</label>
                                <input type="text" name="confirmersomme" class="form-control" id="c.somme"
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
                            <button type="submit" class="btn btn-danger">Valider</button>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-danger">
                        <h2 class="card-title">LISTE DES OPERATIONS DE SORTIE</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Somme</th>
                                    <th>Sous caisse</th>
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
                                        <td class="text-danger">-{{$op->somme}}</td>
                                        <td>{{$op->sous_caisse}}</td>
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
    //Ajax pour faire retrait
    $('#retrait').submit(function() {
        event.preventDefault();
        $('#loader').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'retrait',
            //enctype: 'multipart/form-data',
            data: $('#retrait').serialize(),
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
                Swal.fire({
                    icon: "error",
                    title: "erreur",
                    text: "Impossible de communiquer avec le serveur.",
                    timer: 3600,
                })
            }
        });
        $('#loader').fadeOut(3000);
        return false;
    });

    function formatNumberWithSpaces(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    var input = document.getElementById("somme");
    input.addEventListener("input", function() {
        var value = input.value;
        
        // Supprimer les espaces existants (facultatif)
        value = value.replace(/\s/g, "");

        // Convertir la valeur en nombre entier
        var intValue = parseInt(value, 10);

        // Vérifier si la valeur est un nombre valide
        if (!isNaN(intValue)) {
            // Formater la valeur avec des espaces
            var formattedValue = formatNumberWithSpaces(intValue);
            // alert(formattedValue)

            // Afficher la valeur formatée dans le champ de saisie
            input.value = formattedValue;
        }
    });

    var input1 = document.getElementById("c.somme");
    input1.addEventListener("input", function() {
        var value = input1.value;
        
        // Supprimer les espaces existants (facultatif)
        value = value.replace(/\s/g, "");

        // Convertir la valeur en nombre entier
        var intValue = parseInt(value, 10);

        // Vérifier si la valeur est un nombre valide
        if (!isNaN(intValue)) {
            // Formater la valeur avec des espaces
            var formattedValue = formatNumberWithSpaces(intValue);
            // alert(formattedValue)

            // Afficher la valeur formatée dans le champ de saisie
            input1.value = formattedValue;
        }
    });
});
</script>

</div>
@endsection