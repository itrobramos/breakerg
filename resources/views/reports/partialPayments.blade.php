@extends('layouts.business')

@section('content')
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>

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

            <form action="{{ url('reports/partialpayments') }}" method="POST" id="form">
                <div class="row" style="margin-left:5px; margin-right:20px;">

                    {{-- <div class="col-md-2">
                        <input type="text" class="form-control" id="txtFolio" name="Folio" placeholder="# Remisión"
                            value="{{ isset($Parameters['Folio']) ? $Parameters['Folio'] : '' }}">
                    </div> --}}

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Cliente</label>
                            <select name="clientId" class="form-control select2" id="cmbClientes">
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
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin"
                                value="{{ isset($Parameters['FechaFin']) ? $Parameters['FechaFin'] : '' }}">
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
                    <table class="table table-striped table-bordered" id="table">
                        <thead class="bg-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Pago</th>
                                <th>Saldo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                @php
                                    $fechahoy = new DateTime();
                                    $date = new DateTime($movement->date);
                                @endphp
                                <tr>
                                    <td>{{ $movement->name }}</td>
                                    <td>{{ $date->format('d-m-Y') }}</td>
                                    <td>$ {{ number_format($movement->payment, 2, '.', ',') }}</td>
                                    <td>$ {{ number_format($movement->newDebt, 2, '.', ',') }}</td>
                                    <td>
                                        <a class="btn btn-danger btn-sm button-destroy"
                                            href="{{ route('partialPayments.destroy', ['id' => $movement->id]) }}"
                                            data-original-title="Eliminar" data-method="get"
                                            data-trans-button-cancel="Cancelar" data-trans-button-confirm="Eliminar"
                                            data-trans-title="¿Está seguro de esta operación?"
                                            data-trans-subtitle="Esta operación eliminará este pago parcial">
                                            <i class="fas fa-trash">
                                            </i>
                                            Eliminar
                                        </a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>


    <script>
        $('.select2').select2();

        $(document).ready(function() {
            $('#table').DataTable();
        });


        $('#btnExportar').click(function(e) {
            $("#form").attr('action', '{{ url('reports/partialpayments/export') }}')
            $("#form").submit();
            $("#form").attr('action', '{{ url('reports/partialpayments') }}')
        });
    </script>
@endsection
