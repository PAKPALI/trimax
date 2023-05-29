@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>DEMANDE DEPENSE</h1>
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
                                <h4 class="modal-title">MODIFIER DEPENSE</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="update">
                                @csrf
                                <div class="modal-body">
                                    <input type="text" name="id" class="form-control" id="Id">

                                    <div class="form-group">
                                        <label for="exampleInputText3">Description</label>
                                        <textarea id='Desc' name="desc" class="form-control" id="exampleInputText3"
                                            placeholder="Enter la description"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><small></small></h3>
                    </div>

                    <form id="demande">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Selectionnez sous caisse</label>
                                <select class="form-control select2" name="selection" style="width: 100%;">
                                    <option value="" selected="selected"></option>
                                    @foreach($SC as $sc)
                                        <option value="{{$sc -> id}}">{{strtoupper($sc -> nom)}}</option>
                                    @endforeach
                                </select>
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
                                <label for="exampleInputText">Type de dépense</label>
                                <input type="texte" name="type" class="form-control" id="exampleInputText"
                                    placeholder="Type de dépense">
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
                            <button type="submit" class="btn btn-danger">DEMANDER</button>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-danger">
                        <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Sous caisse</th>
                                    <th>Somme</th>
                                    <th>Type dépense</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1
                                @endphp
                                @foreach($Depense as $d)
                                    <tr>
                                        <td>{{$n++}}</td>
                                        <td>{{strtoupper($d->sousCaisse->nom)}}</td>
                                        <td>{{strtoupper($d->somme)}}</td>
                                        <td>{{strtoupper($d->type)}}</td>
                                        <td>{{strtoupper($d->desc)}}</td>
                                        <td>
                                            @if($d->status == 0)
                                                <span class="badge bg-danger">Rejetée</span>
                                            @elseif($d->status == 1)
                                                <span class="badge bg-success">Acceptée</span>
                                            @else
                                                <span class="badge bg-warning">Non traitée</span>
                                            @endif
                                        </td>
                                        <td>{{$d->created_at}}</td>
                                        <td>
                                            <form class="update">
                                                @csrf
                                                <input type="hidden" value="{{$d -> id}}" name="id">
                                                @if($d -> desc)
                                                    <input type="hidden" value="{{$d -> desc}}" name="desc">
                                                @else
                                                    <input type="hidden" value="desx" name="desc">
                                                @endif
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
                                                <input type="hidden" id="id" value="{{$d -> id}}" name="id">
                                                <button type="submit" class="btn btn-danger"><i class='bx bx-trash'></i>
                                                    Supprimer
                                                </button>
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
        //Ajax pour faire une demande
        $('#demande').submit(function() {
            event.preventDefault();
            $('#loader').fadeIn();
            $.ajax({
                type: 'POST',
                url: 'demande_depense',
                //enctype: 'multipart/form-data',
                data: $('#demande').serialize(),
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
        });
        return false;

        //Selectionner la sous caisse a modifier
        document.querySelectorAll('.update').forEach(_formNode => {
            //console.log(this);
            _formNode.addEventListener('submit', _event => {
                _event.preventDefault();

                var data1 = new FormData(_formNode);

                var FormId = data1.get('id');
                // var FormDesc = data1.get('desc');

                document.getElementById("Id").value = FormId;
                // document.getElementById("Desc").value = FormDesc;

                //envoyez le formulaire au serveur par AJAX
                $('#update').submit(function() {
                    event.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'sous_caisse/update_depense',
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

        //Selectionner la sous caisse a supprimer
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