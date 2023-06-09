@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>SOUS CAISSE</h1>
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
                                <h4 class="modal-title">MODIFIER SOUS CAISSE</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="update">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="id" class="form-control" id="Id">

                                    <label>Saisir le nom</label>
                                    <input type="text" name="nom" class="form-control mb-3" id="Nom"
                                        placeholder="Enter le nom du pays">

                                    <label>Saisir la ville</label>
                                    <input type="text" name="ville" class="form-control mb-3" id="Ville"
                                        placeholder="Enter le nom de la ville">

                                    <label>Saisir le quartier</label>
                                    <input type="text" name="quartier" class="form-control mb-3" id="Quartier"
                                        placeholder="Enter le nom du quartier">
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="add">
                        @csrf
                        <div class="card-body">
                            <!-- <div class="form-group">
                                <label for="exampleInputText0"></label>
                            </div> -->

                            <div class="form-group">
                                <label>Selectionner le pays</label>
                                <select class="form-control select2" name="selection" style="width: 100%;">
                                    <option value="" selected="selected"></option>
                                    @foreach($Pays as $p)
                                        <option value="{{$p -> id}}">{{strtoupper($p -> nom)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Saisir le nom</label>
                                <input type="text" name="nom" class="form-control" id="exampleInputText0"
                                    placeholder="Enter le nom du pays">
                            </div>

                            <div class="form-group">
                                <label>Saisir la ville</label>
                                <input type="text" name="ville" class="form-control" id="exampleInputText0"
                                    placeholder="Enter le nom du ville">
                            </div>

                            <div class="form-group">
                                <label>Saisir le quartier</label>
                                <input type="text" name="quartier" class="form-control" id="exampleInputText0"
                                    placeholder="Enter le nom du quartier">
                            </div>

                            <div id="loader" class="text-center">
                                <img class="animation__shake" src="{{asset('img/trimax.gif')}}" alt="TRIMAX_Logo"
                                    height="70" width="70">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-primary">
                        <h2 class="card-title">LISTE DES SOUS CAISSES</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Nom</th>
                                    <th>Pays</th>
                                    <th>Ville</th>
                                    <th>Quartier</th>
                                    <th>Somme</th>
                                    <th>Date</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1
                                @endphp
                                @foreach($SC as $sc)
                                <tr>
                                    <td>{{$n++}}</td>
                                    <td>{{strtoupper($sc->nom)}}</td>
                                    <td>{{strtoupper($sc->pays->nom)}}</td>
                                    <td>{{strtoupper($sc->ville)}}</td>
                                    <td>{{strtoupper($sc->quartier)}}</td>
                                    <td>{{strtoupper($sc->somme)}}</td>
                                    <td>{{$sc->created_at}}</td>
                                    <td>
                                        <form class="update">
                                            @csrf
                                            <input type="hidden" value="{{$sc -> id}}" name="id">
                                            <input type="hidden" value="{{$sc -> nom}}" name="nom">
                                            <input type="hidden" value="{{$sc -> ville}}" name="ville">
                                            <input type="hidden" value="{{$sc -> quartier}}" name="quartier">
                                            <button type="submit" class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-default">
                                                <i class='bx bx-edit'></i>
                                                Modifier
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="delete">
                                            @csrf
                                            <input type="hidden" id="id" value="{{$sc -> id}}" name="id">
                                            <button type="submit" class="btn btn-danger"><i class='bx bx-trash'></i>
                                                Supprimer</button>
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
                url: 'sous_caisse/ajouter',
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

                var FormId = data1.get('id');
                var FormNom = data1.get('nom');
                var FormVille = data1.get('ville');
                var FormQuartier = data1.get('quartier');

                document.getElementById("Id").value = FormId;
                document.getElementById("Nom").value = FormNom;
                document.getElementById("Ville").value = FormVille;
                document.getElementById("Quartier").value = FormQuartier;

                //envoyez le formulaire au serveur par AJAX
                $('#update').submit(function() {
                    event.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'sous_caisse/update',
                        //enctype: 'multipart/form-data',
                        data: $('#update').serialize(),
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
                    title: "Etes vous sur de vouloir supprimer cette sous caisse?",
                    text: "Cela pourrait entrainer la suppression automatique des donnés lié a la sous caisse",
                    showCancelButton: true,
                    cancelButtonText: 'NON',
                    confirmButtonText: 'OUI',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'sous_caisse/delete',
                            data: $(_formNode).serialize(),
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
                                            window.location.assign(data
                                                .redirect_to)
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
                    }
                })
                return false;
            });
        });
    });
</script>

</div>
@endsection