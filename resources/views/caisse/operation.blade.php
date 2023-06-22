@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>HISTORIQUE DES OPERATIONS</h1>
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


                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form action="{{ route('filterTable') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleInputText0">Type d'operation</label>
                                        <select class="form-control select2" name="type" style="width: 100%;">
                                            <option value="" selected="selected"></option>
                                            <option value="TOUT">DEPOT ET RETRAIT</option>
                                            <option value="DEPOT">DEPOT</option>
                                            <option value="RETRAIT">RETRAIT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Date de debut:</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="Date1" class="form-control datetimepicker-input" data-target="#reservationdate" />
                                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label>Date de fin:</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" name="Date2" class="form-control datetimepicker-input" data-target="#reservationdate1" />
                                            <div class="input-group-append" data-target="#reservationdate1" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="loader" class="text-center">
                                <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"
                                    height="70" width="70">
                            </div>

                            @if($errors ->any())
                                @foreach($errors -> all() as $error)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{$error}}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span style="background-color: white;" aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($TypeErreur == "1")
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{$Message}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span style="background-color: white;" aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                            @endif

                            @if($TypeErreur == "2")
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{$Message}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span style="background-color: white;" aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                            @endif
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-dark">Valider</button>
                            <a href="{{route('caisse.operation')}}" class="btn btn-success">Voir toutes les operations</a>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-dark">
                        <h2 class="card-title">LISTE DES OPERATIONS</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Type_operation</th>
                                    <th>Somme</th>
                                    <th>Banque</th>
                                    <th>Sous caisse</th>
                                    <th>Description</th>
                                    <th>Date de creation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1
                                @endphp
                                @foreach($Operation as $op)
                                    <tr>
                                        <td>{{$n++}}</td>
                                        <!-- somme -->
                                        @if($op -> type_op == "DEPOT")
                                            <td style="color:blue;">
                                                {{$op -> type_op}}
                                            </td>
                                            <td style="color:blue;">
                                                +{{$op -> somme}}
                                            </td>
                                        @else
                                            <td style="color:red;">
                                                {{$op -> type_op}}
                                            </td>
                                            <td style="color:red;">
                                                -{{$op -> somme}}
                                            </td>
                                        @endif
                                        <!-- banque -->
                                        @if($op->banque_id)
                                            <td>{{strtoupper($op->banque->nom)}}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <!-- sous caisse -->
                                        @if($op->sous_caisse)
                                            <td>{{strtoupper($op->sous_caisse)}}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <!-- description -->
                                        <td>{{strtoupper($op->desc)}}</td>
                                        <!-- created at -->
                                        <td>{{strtoupper($op->created_at)}}</td>
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
    //Ajax pour ajouter pays
    $('#add').submit(function() {
        event.preventDefault();
        $('#loader').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'banque/ajouter',
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

    //Selectionner le pays a modifier
    document.querySelectorAll('.update').forEach(_formNode => {
        //console.log(this);
        _formNode.addEventListener('submit', _event => {
            _event.preventDefault();

            var data1 = new FormData(_formNode);
            console.log(data1.get('id'));
            console.log(data1.get('nom'));

            var FormId = data1.get('id');
            var FormNom = data1.get('nom');

            document.getElementById("Id").value = FormId;
            document.getElementById("Nom").value = FormNom;

            //envoyez le formulaire au serveur par AJAX
            $('#mp').submit(function() {
                event.preventDefault();
                $('#update_loader').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: 'banque/update',
                    //enctype: 'multipart/form-data',
                    data: $('#mp').serialize(),
                    datatype: 'json',
                    success: function(data) {
                        //var object = JSON.parse(data);
                        console.log(data)
                        if (data.status) {
                            Swal.fire({
                                icon: "success",
                                title: data.title,
                                text: data.msg,
                            }).then(() => {
                                if (data.redirect_to != null) {
                                    window.location.assign(data
                                        .redirect_to)
                                } else {
                                    window.location.reload()
                                }
                                //window.location.reload();
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
                return false;
            });
            return false;
        });
    });

    //Selectionner le pays a supprimer
    document.querySelectorAll('.delete').forEach(_formNode => {
        _formNode.addEventListener('submit', _event => {
            event.preventDefault();

            Swal.fire({
                icon: "question",
                title: "Etes vous sur de vouloir supprimer cette banque?",
                text: "Cela pourrait entrainer la suppression automatique des donnés lié a cette banque",
                showCancelButton: true,
                cancelButtonText: 'NON',
                confirmButtonText:  'OUI',
                confirmButtonColor: '#d33',
                cancelButtonColor:  '#3085d6',
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'banque/delete',
                        //enctype: 'multipart/form-data',
                        data: $(_formNode).serialize(),
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
                                    if(data.redirect_to != null) {
                                        window.location.assign(data.redirect_to)
                                    }else{
                                        window.location.reload()
                                    }
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
                }
            })
            return false;
        });
    });
});
</script>

</div>
@endsection