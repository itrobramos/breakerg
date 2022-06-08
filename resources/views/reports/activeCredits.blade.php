@extends('layouts.business')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Créditos activos</h1>
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

            <form id="form" action="{{ url('reports/activecredits') }}" method="POST">
                <div class="row" style="margin-left:5px; margin-right:20px;">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1"># Remisión</label>
                            <input type="text" class="form-control" id="txtFolio" name="Folio"
                                value="{{ isset($Parameters['Folio']) ? $Parameters['Folio'] : '' }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Cliente</label>
                            <select name="clientId" class="form-control" id="cmbClientes">
                                <option value="">-Seleccione Cliente-</option>
                                @foreach ($clients as $c => $item)
                                    @if (isset($Parameters['ClientId']) && $Parameters['ClientId'] == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio"
                                value="{{ isset($Parameters['FechaInicio']) ? $Parameters['FechaInicio'] : '' }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Fecha Fin</label>
                            <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento"
                                value="{{ isset($Parameters['FechaVencimiento']) ? $Parameters['FechaVencimiento'] : '' }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1" style="height:40px;"></label>
                            <button class="btn btn-success btn-md" type="submit">Buscar</button>
                            <button class="btn btn-dark btn-md" id="btnExportar" type="button">Exportar</button>
                        </div>
                    </div>

                </div>
            </form>

            <br>
            <br>

            <div class="row">
                <div class="col-12">
                    <table class="table table-hover" id="table">
                        <thead>
                            <tr class="bg-dark">
                                <th># Remisión</th>
                                <th>Cliente</th>
                                <th>Días restantes</th>
                                <th>Fecha vencimiento</th>
                                <th>Monto de venta</th>
                                <th>Monto pendiente</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($credits as $credit)
                            @php
                                $fechahoy = new DateTime();
                                $endDate = new DateTime($credit->endDate);
                            @endphp
                            <tr>
                                <td>{{ $credit->folio }}</td>
                                <td>{{ $credit->name }}</td>
                                <td>{{ $fechahoy->diff($endDate)->format('%a') }}</td>
                                <td>{{ $endDate->format('d-m-Y') }}</td>
                                <td>$ {{ number_format($credit->total, 2, '.', ',') }}</td>
                                <td>$ {{ number_format($credit->currentCredit, 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        
                    </table>
                </div>


            </div>

        </div>

    </div>


    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });

        $('#btnExportar').click(function(e) {
            $("#form").attr('action', '{{ url('reports/activecredits/export') }}')
            $("#form").submit();
            $("#form").attr('action', '{{ url('reports/activecredits') }}')
        })
    </script>
@endsection
