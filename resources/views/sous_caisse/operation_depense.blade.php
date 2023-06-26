@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>HISTORIQUE DES OPERATIONS DE DEPENSE DE LA SOUS CAISSE</h1>
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

                    <form action="{{ route('filterTableSousCaisseDepense') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @if(Auth::user()->type_user == 1)
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="exampleInputText0">Sous caisse</label>
                                            <select class="form-control select2" name="sousCaisse" style="width: 100%;">
                                                <option value="" selected="selected"></option>
                                                @foreach($SC as $sc)
                                                    <option value="{{$sc->id}}">{{$sc->nom}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="type" value="TOUT" class="form-control" placeholder="">
                                    </div>
                                @else
                                    <input type="hidden" name="sousCaisse" value="{{Auth::user()->sous_caisse_id}}" class="form-control" placeholder="">
                                @endif
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleInputText0">Type depense</label>
                                        <select class="form-control select2" name="type_depense" style="width: 100%;">
                                            <option value="" selected="selected"></option>
                                            <option value="2">DEPENSE NON TRAITEE</option>
                                            <option value="1">DEPENSE VALIDEE</option>
                                            <option value="0">DEPENSE REJETEE</option>
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
                            <a href="{{route('sous_caisse.operation_depense')}}" class="btn btn-success">Voir toutes les operations</a>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header bg-dark">
                        <h2 class="card-title">LISTE DES OPERATIONS DE DEPENSES DE LA SOUS CAISSE</h2>
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
$(function() {
    $('#loader').hide();
});
</script>

</div>
@endsection