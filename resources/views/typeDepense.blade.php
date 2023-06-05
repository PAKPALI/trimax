@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>TYPE DE DEPENSE</h1>
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

                <div class="modal fade" id="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">MODIFIER TYPE DE DEPENSE</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="mp">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="id" class="form-control" id="Id">
                                    <input type="text" name="nom" class="form-control" id="Nom" placeholder="Enter le nom du pays">
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="add">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputText0">Ajouter type de depense</label>
                                <input type="text" name="nom" class="form-control" id="exampleInputText0"
                                    placeholder="Enter le nom du type de pense">
                            </div>
                            <div id="loader" class="text-center">
                                <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"
                                    height="70" width="70">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">Valider</button>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-warning">
                        <h2 class="card-title">LISTE DES TYPE DE DEPENSE</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Nom</th>
                                    <th>Date</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1
                                @endphp
                                @foreach($TypeDepense as $t)
                                <tr>
                                    <td>{{$n++}}</td>
                                    <td>{{strtoupper($t->nom)}}</td>
                                    <td>{{$t->created_at}}</td>
                                    <td>
                                        <form class="update">
                                            @csrf
                                            <input type="hidden" value="{{$t -> id}}" name="id">
                                            <input type="hidden" value="{{$t -> nom}}" name="nom">
                                            <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                                                <i class='bx bx-edit'></i>
                                                Modifier
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="delete">
                                            @csrf
                                            <input type="hidden" id="id" value="{{$t -> id}}" name="id">
                                            <button type="submit" class="btn btn-danger"><i class='bx bx-trash'></i> Supprimer</button>
                                        </form>
                                    </td>
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
            url: 'type_depense/ajouter',
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
                    url: 'type_depense/update',
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
        });
    });

    //Selectionner le pays a supprimer
    document.querySelectorAll('.delete').forEach(_formNode => {
        _formNode.addEventListener('submit', _event => {
            event.preventDefault();

            Swal.fire({
                icon: "question",
                title: "Etes vous sur de vouloir supprimer ce type de depense?",
                text: "Cela pourrait entrainer la suppression automatique des donnés lié a ce type de depense",
                showCancelButton: true,
                cancelButtonText: 'NON',
                confirmButtonText:  'OUI',
                confirmButtonColor: '#d33',
                cancelButtonColor:  '#3085d6',
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'type_depense/delete',
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