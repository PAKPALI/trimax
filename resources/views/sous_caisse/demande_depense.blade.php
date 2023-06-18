@extends('layouts.layout')
@section('content')

@if($s1)
    <section class="content-header">
        <div class="col-12 col-sm-12 col-md-12 mt-5">
            <div class="info-box mb-3">
                @if($s1->somme>0)
                    <span class="info-box-icon bg-success elevation-1">FCFA</span>
                @else
                    <span class="info-box-icon bg-danger elevation-1">FCFA</span>
                @endif
                <div class="info-box-content">
                    <span class="info-box-text">
                        <h1>Somme Totale</h1>
                    </span>
                    <span class="info-box-number">
                        <h2>
                            {{$s1->somme}}
                        </h2>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endif

@if (Auth::user()->type_user ==2 AND Auth::user()->sous_caisse_id !=null AND Auth::user()->connected == 1)
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
                                        <input type="hidden" name="id" class="form-control" id="Id">

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
                
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><small></small></h3>
                        </div>

                        <form id="demande">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" value="{{Auth::user()->id}}" name="user_id" class="form-control" id="">
                                <input type="hidden" value="{{Auth::user()->sous_caisse_id}}" name="selection" class="form-control" id="">
                            
                                <div class="form-group">
                                    <label>Selectionnez type de depense</label>
                                    <select class="form-control select2" name="type" style="width: 100%;">
                                        <option value="" selected="selected"></option>
                                        @foreach($TypeDepense as $t)
                                            <option value="{{$t -> id}}">{{strtoupper($t -> nom)}}</option>
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
                                <button type="submit" class="btn btn-warning">DEMANDER</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- liste de toutes les depenses -->
                    <div class="card mt-5">
                        <div class="card-header bg-warning">
                            <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES NON TRAITEES</h2>
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
                                        <th>Utilisateur</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Modifier</th>
                                        @if (Auth::user()->type_user ==1)
                                        <th>Valider</th>
                                        <th>Refuser</th>
                                        @endif
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
                                            <td>{{$d->desc}}</td>
                                            <td>{{$d->user->nom}}</td>
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
                                            <!-- update -->
                                            @if($d->status == 2)
                                                <td>
                                                    <form class="update">
                                                        @csrf
                                                        <input type="hidden" value="{{$d -> id}}" name="id">
                                                        <input type="hidden" value="{{$d -> desc}}" name="desc">
                                                        <button type="submit" class="btn btn-warning" data-toggle="modal"
                                                            data-target="#modal-default">
                                                            <i class='fas fa-edit'></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @else
                                                <td>
                                                    <form class="update">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning" disabled>
                                                            <i class='fas fa-edit'></i>
                                                            Modifier
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                            <!-- action -->
                                            @if (Auth::user()->type_user ==1)
                                                @if($d->status == 2)
                                                    <td  style="text-align: center;">
                                                        <form class="valider">
                                                            @csrf
                                                            <input type="hidden" id="id" value="{{$d -> id}}" name="id">
                                                            <input type="hidden" id="somme" value="{{$d -> somme}}" name="somme">
                                                            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td  style="text-align: center;">
                                                        <form class="rejeter">
                                                            @csrf
                                                            <input type="hidden" id="id" value="{{$d -> id}}" name="id">
                                                            <button type="submit" class="btn btn-danger"><i class="far fa-times-circle"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                @else
                                                <td  style="text-align: center;">
                                                    <button type="submit" class="btn btn-success" disabled><i class="fas fa-check"></i>
                                                    </button>
                                                </td>
                                                <td  style="text-align: center;">
                                                    <button type="submit" class="btn btn-danger" disabled><i class="far fa-times-circle"></i>
                                                    </button>
                                                </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>

                    <!-- liste des depenses valides -->
                    <div class="card mt-5">
                        <div class="card-header bg-success">
                            <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES VALIDEES</h2>
                        </div>

                        <div class="card-body">
                            <table id="example0" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>N*</th>
                                        <th>Sous caisse</th>
                                        <th>Somme</th>
                                        <th>Type dépense</th>
                                        <th>Description</th>
                                        <th>Utilisateurs</th>
                                        <th>Validateur</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $n = 1
                                    @endphp
                                    @foreach($Depense_v as $d)
                                        <tr>
                                            <td>{{$n++}}</td>
                                            <td>{{strtoupper($d->sousCaisse->nom)}}</td>
                                            <td>{{strtoupper($d->somme)}}</td>
                                            <td>{{strtoupper($d->type)}}</td>
                                            <td>{{$d->desc}}</td>
                                            <td>{{$d->user->nom}}</td>
                                            <td>{{$d->validateur}}</td>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>

                    <!-- liste des depenses rejetes -->
                    <div class="card mt-5">
                        <div class="card-header bg-danger">
                            <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES REJETEES</h2>
                        </div>

                        <div class="card-body">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>N*</th>
                                        <th>Sous caisse</th>
                                        <th>Somme</th>
                                        <th>Type dépense</th>
                                        <th>Description</th>
                                        <th>Utilisateur</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $n = 1
                                    @endphp
                                    @foreach($Depense_r as $d)
                                        <tr>
                                            <td>{{$n++}}</td>
                                            <td>{{strtoupper($d->sousCaisse->nom)}}</td>
                                            <td>{{strtoupper($d->somme)}}</td>
                                            <td>{{strtoupper($d->type)}}</td>
                                            <td>{{$d->desc}}</td>
                                            <td>{{$d->user->nom}}</td>
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
@else
    @if (Auth::user()->type_user ==1)
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
                                            <input type="hidden" name="id" class="form-control" id="Id">

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
                        <!-- liste de toutes les depenses -->
                        <div class="card mt-5">
                            <div class="card-header bg-warning">
                                <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES NON TRAITEES</h2>
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
                                            <th>Utilisateur</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Modifier</th>
                                            @if (Auth::user()->type_user ==1)
                                            <th>Valider</th>
                                            <th>Refuser</th>
                                            @endif
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
                                                <td>{{$d->desc}}</td>
                                                <td>{{$d->user->nom}}</td>
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
                                                <!-- update -->
                                                @if($d->status == 2)
                                                    <td>
                                                        <form class="update">
                                                            @csrf
                                                            <input type="hidden" value="{{$d -> id}}" name="id">
                                                            <input type="hidden" value="{{$d -> desc}}" name="desc">
                                                            <button type="submit" class="btn btn-warning" data-toggle="modal"
                                                                data-target="#modal-default">
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                @else
                                                    <td>
                                                        <form class="update">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning" disabled>
                                                                <i class='fas fa-edit'></i>
                                                                Modifier
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                                <!-- action -->
                                                @if (Auth::user()->type_user ==1)
                                                    @if($d->status == 2)
                                                        <td  style="text-align: center;">
                                                            <form class="valider">
                                                                @csrf
                                                                <input type="hidden" id="id" value="{{$d -> id}}" name="id">
                                                                <input type="hidden" id="somme" value="{{$d -> somme}}" name="somme">
                                                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td  style="text-align: center;">
                                                            <form class="rejeter">
                                                                @csrf
                                                                <input type="hidden" id="id" value="{{$d -> id}}" name="id">
                                                                <button type="submit" class="btn btn-danger"><i class="far fa-times-circle"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @else
                                                    <td  style="text-align: center;">
                                                        <button type="submit" class="btn btn-success" disabled><i class="fas fa-check"></i>
                                                        </button>
                                                    </td>
                                                    <td  style="text-align: center;">
                                                        <button type="submit" class="btn btn-danger" disabled><i class="far fa-times-circle"></i>
                                                        </button>
                                                    </td>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>

                        <!-- liste des depenses valides -->
                        <div class="card mt-5">
                            <div class="card-header bg-success">
                                <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES VALIDEES</h2>
                            </div>

                            <div class="card-body">
                                <table id="example95" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>N*</th>
                                            <th>Sous caisse</th>
                                            <th>Somme</th>
                                            <th>Type dépense</th>
                                            <th>Description</th>
                                            <th>Utilisateur</th>
                                            <th>Validateur</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $n = 1
                                        @endphp
                                        @foreach($Depense_v as $d)
                                            <tr>
                                                <td>{{$n++}}</td>
                                                <td>{{strtoupper($d->sousCaisse->nom)}}</td>
                                                <td>{{strtoupper($d->somme)}}</td>
                                                <td>{{strtoupper($d->type)}}</td>
                                                <td>{{$d->desc}}</td>
                                                <td>{{$d->user->nom}}</td>
                                                <td>{{$d->validateur}}</td>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>

                        <!-- liste des depenses rejetes -->
                        <div class="card mt-5">
                            <div class="card-header bg-danger">
                                <h2 class="card-title">LISTE DE DEMANDES DE DEPENSES REJETEES</h2>
                            </div>

                            <div class="card-body">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>N*</th>
                                            <th>Sous caisse</th>
                                            <th>Somme</th>
                                            <th>Type dépense</th>
                                            <th>Description</th>
                                            <th>Utilisateur</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $n = 1
                                        @endphp
                                        @foreach($Depense_r as $d)
                                            <tr>
                                                <td>{{$n++}}</td>
                                                <td>{{strtoupper($d->sousCaisse->nom)}}</td>
                                                <td>{{strtoupper($d->somme)}}</td>
                                                <td>{{strtoupper($d->type)}}</td>
                                                <td>{{$d->desc}}</td>
                                                <td>{{$d->user->nom}}</td>
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
    @endif
@endif

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
            return false;
        });

        //Selectionner la epense a modifier
        document.querySelectorAll('.update').forEach(_formNode => {
            //console.log(this);
            _formNode.addEventListener('submit', _event => {
                _event.preventDefault();

                var data1 = new FormData(_formNode);

                var FormId = data1.get('id');
                var FormDesc = data1.get('desc');

                document.getElementById("Id").value = FormId;
                document.getElementById("Desc").value = FormDesc;

                //envoyez le formulaire au serveur par AJAX
                $('#update').submit(function() {
                    event.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'update_depense',
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

        //Selectionner la demande a valider
        document.querySelectorAll('.valider').forEach(_formNode => {
            _formNode.addEventListener('submit', _event => {
                event.preventDefault();

                Swal.fire({
                    icon: "question",
                    title: "Etes vous sur de vouloir valider cette demande?",
                    text: "L'action est irreversible!",
                    showCancelButton: true,
                    cancelButtonText: 'NON',
                    confirmButtonText: 'OUI',
                    confirmButtonColor: 'green',
                    cancelButtonColor: 'red',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'valider_depense',
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

        //Selectionner la demande a valider
        document.querySelectorAll('.rejeter').forEach(_formNode => {
            _formNode.addEventListener('submit', _event => {
                event.preventDefault();

                Swal.fire({
                    icon: "question",
                    title: "Etes vous sur de vouloir rejeter cette demande?",
                    text: "L'action est irreversible!",
                    showCancelButton: true,
                    cancelButtonText: 'NON',
                    confirmButtonText: 'OUI',
                    confirmButtonColor: 'red',
                    cancelButtonColor: 'black',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'rejeter_depense',
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