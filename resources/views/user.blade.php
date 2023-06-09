@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>UTILISATEURS</h1>
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
                                <h4 class="modal-title">MODIFIER UTILISATEUR</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="mp">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="id" class="form-control" id="Id">
                                    <input type="text" name="nom" class="form-control" id="Nom" placeholder="Nom">
                                </div>
                                <div class="modal-body">
                                    <input type="email" name="email" class="form-control" id="Email" placeholder="Email">
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- parametrer-->
                <div class="modal fade" id="modal-gerer">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">PARAMETRER UTILISATEUR</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="param">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="id" class="form-control" id="Id_p">
                                    <label>Selectionnez sous caisse</label>
                                    <select class="form-control select2" name="sousCaisse" style="width: 100%;">
                                        <option value="" selected="selected">Selectionnez sous caisse</option>
                                        <option value="0">Aucune sous caisse</option>
                                        @foreach($SC as $sc)
                                            <option value="{{$sc -> id}}">{{strtoupper($sc -> nom)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-dark">Modifier</button>
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
                            <div class="form-group">
                                <label for="exampleInputText0">Nom</label>
                                <input type="text" name="nom" class="form-control" id="exampleInputText0"
                                    placeholder="Nom">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText1">Email</label>
                                <input type="email" name="email" class="form-control" id="exampleInputText1"
                                    placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText2">Mot de passe</label>
                                <input type="password" name="password" class="form-control" id="exampleInputText2"
                                    placeholder="mot de passe">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputText3">Confirmer mot de passe</label>
                                <input type="password" name="password_confirmation" class="form-control" id="exampleInputText3"
                                    placeholder="Confirmez mot de passe">
                            </div>
                            <!-- loader -->
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
                        <h2 class="card-title">LISTE DES UTILISATEURS</h2>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N*</th>
                                    <th>Sous caisse</th>
                                    <th>Nom</th>
                                    <th>connecter</th>
                                    <th>gerer client</th>
                                    <th>Date</th>
                                    <th>Parametrer</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1
                                @endphp

                                @foreach($User as $u)
                                <tr>
                                    <td>{{$n++}}</td>
                                    <td>
                                        @if($u->sous_caisse_id == null)
                                            <span class="badge bg-danger">Pas de sous caisse</span>
                                        @else
                                            <span class="badge bg-success">{{strtoupper($u->sousCaisse->nom)}}</span>
                                        @endif
                                    </td>
                                    <td>{{strtoupper($u->nom)}}</td>
                                    <td>
                                        <form id="connected" class="connected">
                                            @csrf
                                            <input type="hidden" value="{{$u -> id}}" name="id">
                                            <input type="hidden" value="{{$u -> connected}}" name="connected">
                                            @if($u -> connected ==0)
                                                <input type="checkbox" id="check" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                            @else
                                                <input type="checkbox" id="check" name="" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                            @endif
                                        </form>
                                    </td>
                                    <td>
                                        <form id="status_client" class="status_client">
                                            @csrf
                                            <input type="hidden" value="{{$u -> id}}" name="id">
                                            <input type="hidden" value="{{$u -> status_client}}" name="status_client">
                                            @if($u -> status_client ==0)
                                                <input type="checkbox" id="check" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                            @else
                                                <input type="checkbox" id="check" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                            @endif
                                        </form>
                                    </td>
                                    <td>{{$u->created_at}}</td>
                                    <td>
                                        <form class="parametrer">
                                            @csrf
                                            <input type="hidden" value="{{$u -> id}}" name="id">
                                            <!-- <input type="hidden" value="{{$u -> nom}}" name="nom">
                                            <input type="hidden" value="{{$u -> email}}" name="email"> -->
                                            <button type="submit" class="btn btn-dark" data-toggle="modal" data-target="#modal-gerer">
                                                <i class='bx bx-setting'></i>
                                                Parametrer
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="update">
                                            @csrf
                                            <input type="hidden" value="{{$u -> id}}" name="id">
                                            <input type="hidden" value="{{$u -> nom}}" name="nom">
                                            <input type="hidden" value="{{$u -> email}}" name="email">
                                            <button type="submit" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#modal-default">
                                                <i class='fas fa-edit'></i>
                                                Modifier
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="delete">
                                            @csrf
                                            <input type="hidden" id="id" value="{{$u -> id}}" name="id">
                                            <button type="submit" class="btn btn-danger"><i class='fas fa-trash'></i> Supprimer</button>
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
    //Ajax pour ajouter user
    $('#add').submit(function() {
        event.preventDefault();
        $('#loader').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'utilisateurs/ajouter',
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
    document.querySelectorAll('.parametrer').forEach(_formNode => {
        //console.log(this);
        _formNode.addEventListener('submit', _event => {
            _event.preventDefault();

            var data1 = new FormData(_formNode);
            console.log(data1.get('id'));
            // console.log(data1.get('nom'));
            // console.log(data1.get('email'));

            var FormId = data1.get('id');
            // var FormNom = data1.get('nom');
            // var FormEmail = data1.get('email');

            document.getElementById("Id_p").value = FormId;
            // document.getElementById("Nom").value = FormNom;
            // document.getElementById("Email").value = FormEmail;

            //envoyez le formulaire au serveur par AJAX
            $('#param').submit(function() {
                event.preventDefault();
                $('#update_loader').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: 'utilisateurs/parametre',
                    //enctype: 'multipart/form-data',
                    data: $('#param').serialize(),
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
        return false;
    });

    //Selectionner le user a modifier
    document.querySelectorAll('.update').forEach(_formNode => {
        //console.log(this);
        _formNode.addEventListener('submit', _event => {
            _event.preventDefault();

            var data1 = new FormData(_formNode);
            console.log(data1.get('id'));
            console.log(data1.get('nom'));
            console.log(data1.get('email'));

            var FormId = data1.get('id');
            var FormNom = data1.get('nom');
            var FormEmail = data1.get('email');

            document.getElementById("Id").value = FormId;
            document.getElementById("Nom").value = FormNom;
            document.getElementById("Email").value = FormEmail;

            //envoyez le formulaire au serveur par AJAX
            $('#mp').submit(function() {
                event.preventDefault();
                $('#update_loader').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: 'utilisateurs/update',
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
        return false;
    });

    //Selectionner le user a supprimer
    document.querySelectorAll('.delete').forEach(_formNode => {
        _formNode.addEventListener('submit', _event => {
            event.preventDefault();

            Swal.fire({
                icon: "question",
                title: "Etes vous sur de vouloir supprimer cet utilisateur?",
                text: "Cela pourrait entrainer la suppression automatique des donnés lié a cet utilisateur",
                showCancelButton: true,
                cancelButtonText: 'NON',
                confirmButtonText:  'OUI',
                confirmButtonColor: '#d33',
                cancelButtonColor:  '#3085d6',
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'utilisateurs/delete',
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

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch();
    })

    // Selectionner le user a modifier
    document.querySelectorAll('.connected').forEach(_formNode => {
        var data1 = new FormData(_formNode);
        var checkbox = $(_formNode).find("input[type='checkbox']");

        var Id = data1.get('id');

        checkbox.on("switchChange.bootstrapSwitch", function(event, state) {
            //envoyez le formulaire au serveur par AJAX
            var switchState = $(_formNode).find("input[type='checkbox']").bootstrapSwitch('state');
            if(switchState){
                var dataToSend = {
                    connected: 1,
                    id: Id
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Récupère automatiquement le token CSRF depuis la balise meta
                    },
                    type: 'POST',
                    url: 'utilisateurs/connected',
                    //enctype: 'multipart/form-data',
                    data: dataToSend,
                    datatype: 'json',
                    success: function(data) {
                        //var object = JSON.parse(data);
                        console.log(data)
                        if (data.status) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                icon: "success",
                                title: data.title,
                                text: data.msg,
                                timer: 3000
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
            }else{
                var dataToSend = {
                    connected: 0,
                    id: Id
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Récupère automatiquement le token CSRF depuis la balise meta
                    },
                    type: 'POST',
                    url: 'utilisateurs/connected',
                    //enctype: 'multipart/form-data',
                    data: dataToSend,
                    datatype: 'json',
                    success: function(data) {
                        //var object = JSON.parse(data);
                        console.log(data)
                        if (data.status) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                icon: "success",
                                title: data.title,
                                text: data.msg,
                                timer: 3000
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
            }
            return false;
        });
        return false;
    });

    document.querySelectorAll('.status_client').forEach(_formNode => {
        var data1 = new FormData(_formNode);
        var checkbox = $(_formNode).find("input[type='checkbox']");

        var Id = data1.get('id');

        checkbox.on("switchChange.bootstrapSwitch", function(event, state) {
            //envoyez le formulaire au serveur par AJAX
            var switchState = $(_formNode).find("input[type='checkbox']").bootstrapSwitch('state');
            if(switchState){
                var dataToSend = {
                    connected: 1,
                    id: Id
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Récupère automatiquement le token CSRF depuis la balise meta
                    },
                    type: 'POST',
                    url: 'utilisateurs/status',
                    //enctype: 'multipart/form-data',
                    data: dataToSend,
                    datatype: 'json',
                    success: function(data) {
                        //var object = JSON.parse(data);
                        console.log(data)
                        if (data.status) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                icon: "success",
                                title: data.title,
                                text: data.msg,
                                timer: 3000
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
            }else{
                var dataToSend = {
                    connected: 0,
                    id: Id
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Récupère automatiquement le token CSRF depuis la balise meta
                    },
                    type: 'POST',
                    url: 'utilisateurs/status',
                    //enctype: 'multipart/form-data',
                    data: dataToSend,
                    datatype: 'json',
                    success: function(data) {
                        //var object = JSON.parse(data);
                        console.log(data)
                        if (data.status) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                icon: "success",
                                title: data.title,
                                text: data.msg,
                                timer: 3000
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
            }
            return false;
        });
        return false;
    });

});
</script>

</div>
@endsection