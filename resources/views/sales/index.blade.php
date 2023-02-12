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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Ventas</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Ventas</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="card">

            <div class="card-header">
                <div class="row justify-content-between" style="padding-right:20px;">
                    <a href="{{ url('reports') }}">
                        <button type="button" class="btn btn-danger">Regresar</button>
                    </a>
                </div>
            </div>

            <div class="card-body">

                <form action="{{ url('sales') }}" method="POST" id="form">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Folio</label>
                                <input type="number" class="form-control" id="txtFolio" name="Folio" placeholder="Folio"
                                    value="{{ isset($Parameters['Folio']) ? $Parameters['Folio'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Cliente</label>
                                <select name="clientId" class="form-control select2" id="cmbClientes">
                                    <option value="">Cliente</option>
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
                                <input type="date" class="form-control" id="txtFechaInicio" name="FechaInicio"
                                    value="{{ isset($Parameters['FechaInicio']) ? $Parameters['FechaInicio'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fecha Fin</label>
                                <input type="date" class="form-control" id="txtFechaFin" name="FechaFin"
                                    value="{{ isset($Parameters['FechaFin']) ? $Parameters['FechaFin'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="exampleInputEmail1" style="height:40px;"></label>
                            <button class="btn btn-success btn-md" type="submit">Buscar</button>
                            <button class="btn btn-dark btn-md" id="btnExportar" type="button">Exportar</button>
                        </div>

                    </div>
                </form>

                <br>


                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($objects as $object)
                            <tr>
                                <td>{{ @$object->folio }}</td>
                                <td>{{ @$object->date }}</td>
                                <td>{{ @$object->client->name }}</td>
                                <td>$ {{ number_format(@$object->total, 2, '.', ',') }}</td>

                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('sales.show', ['id' => $object->id]) }}">
                                        <i class="fas fa-eye">
                                        </i>
                                        Detalles
                                    </a>

                                    @if (isset($object->imageUrl))
                                        <a class="btn btn-warning btn-sm" href="{{ $object->imageUrl }}"
                                            target="n_blank">
                                            <i class="fas fa-file"></i>
                                        </a>
                                    @endif

                                    <a class="btn btn-primary btn-sm btnTimbrar" href="#"
                                    data-id="{{ $object->id }}">
                                    <i class="fas fa-check">
                                    </i>
                                    Timbrar
                                </a>
                                    <a class="btn btn-danger btn-sm button-destroy"
                                        href="{{ route('sales.destroy', ['id' => $object->id]) }}"
                                        data-original-title="Eliminar" data-method="get" data-trans-button-cancel="Cancelar"
                                        data-trans-button-confirm="Eliminar"
                                        data-trans-title="¿Está seguro de esta operación?"
                                        data-trans-subtitle="Esta operación eliminará este registro permanentemente">
                                        <i class="fas fa-trash">
                                        </i>

                                    </a>
                                </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalTimbrar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Timbrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sales.timbrar') }}" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" id="txtId">
                            <label for="exampleInputEmail1">Rfc</label>
                            <input type="text" class="form-control" value="" id="txtRfc" name="rfc"
                                required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Timbrar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        $('.select2').select2();

        $(document).ready(function() {
            $('#table').DataTable();
        });

        $('#btnExportar').click(function(e) {
            $("#form").attr('action', '{{ url('sales/export') }}')
            $("#form").submit();
            $("#form").attr('action', '{{ url('sales') }}')
        })

        $('.btnTimbrar').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id')
                $('#txtId').val(id);
                $('#modalTimbrar').modal('show')
        })

    </script>
@endsection
