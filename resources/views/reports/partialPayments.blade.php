@extends('layouts.business')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Pagos Parciales</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Reportes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-left:20px; margin-right:20px;">

            <div class="card-header">
                <div class="row justify-content-between" style="padding-right:20px;">
                    <a href="{{ url('reports') }}">
                        <button type="button" class="btn btn-danger">Regresar</button>
                    </a>
                </div>
            </div>

            <form action="{{ url('reports/activecredits') }}" method="POST">
                <div class="row" style="margin-left:5px; margin-right:20px;">

                    <div class="col-md-2">
                        <input type="text" class="form-control" id="txtFolio" name="Folio" placeholder="# Remisión"
                            value="{{ isset($Parameters['Folio']) ? $Parameters['Folio'] : '' }}">
                    </div>

                    <div class="col-md-3">
                        <select name="clientId" class="form-control" id="cmbClientes">
                            <option value="">-Seleccione Cliente-</option>
                            @foreach ($clients as $c => $item)
                                @if(isset($Parameters['ClientId']) && $Parameters['ClientId'] == $item->id )
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento"
                            value="{{ isset($Parameters['FechaVencimiento']) ? $Parameters['FechaVencimiento'] : '' }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success btn-md" type="submit">Buscar</button>
                    </div>

                </div>
            </form>

            <br>
            <br>

            <div class="row">

                <table class="table table-hover">
                    <tr class="bg-dark">
                        <td>Cliente</td>
                        <td>Fecha</td>
                        <td>Pago</td>
                        <td>Saldo</td>
                    </tr>

                    @foreach ($movements as $movement)
                        @php 
                            $fechahoy = new DateTime(); 
                            $date = new DateTime($movement->created_at); 
                        @endphp
                        <tr>
                            <td>{{$movement->name}}</td>
                            <td>{{ $date->format('d-m-Y') }}</td>
                            <td>$ {{$movement->payment}}</td>
                            <td>$ {{$movement->newDebt}}</td>
                        </tr>
                    @endforeach
                </table>

            </div>

        </div>

    </div>


    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
