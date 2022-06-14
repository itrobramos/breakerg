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
                        <h1 class="m-0 text-dark">Entradas de mercancía</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Entradas de mercancía</li>
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
                    <a href="{{ url('entries/add') }}">
                        <button type="button" class="btn btn-success">Agregar</button>
                    </a>
                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{ url('entries') }}" method="POST" id="form">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Proveedor</label>
                                <select name="supplierId" class="form-control select2" id="cmbSupplier">
                                    <option value="">Todos  </option>
                                    @foreach ($suppliers as $c => $item)
                                        @if (isset($Parameters['SupplierId']) && $Parameters['SupplierId'] == $item->id)
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
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($objects as $object)
                            <tr>
                                <td>{{ $object->supplier->name }}</td>
                                <td>{{ $object->date }}</td>
                                <td>$ {{ number_format($object->totalCost, 2, '.', ',') }}</td>

                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('entries.show', ['id' => $object->id]) }}">
                                        <i class="fas fa-eye">
                                        </i>
                                        Detalles
                                    </a>

                                    <a class="btn btn-danger btn-sm button-destroy"
                                        href="{{ route('entries.destroy', ['id' => $object->id]) }}"
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



    <script>
        $('.select2').select2();

        $(document).ready(function() {
            $('#table').DataTable();
        });

        $('#btnExportar').click(function(e) {
            $("#form").attr('action', '{{ url('entries/export') }}')
            $("#form").submit();
            $("#form").attr('action', '{{ url('entries') }}')
        })
    </script>
@endsection
