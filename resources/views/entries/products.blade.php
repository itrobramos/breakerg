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
                        <h1 class="m-0 text-dark">Entradas Detallado</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Entradas</li>
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

                <form action="{{ url('entries/products') }}" method="POST" id="form">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Producto</label>
                                <select name="productId" id="productId" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($products as $c => $item)
                                        @if (isset($Parameters['ProductId']) && $Parameters['ProductId'] == $item->id)
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
                                <label for="exampleInputEmail1">Proveedor</label>
                                <select name="supplierId" class="form-control select2" id="cmbSuppliers">
                                    <option value="">Todos</option>
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
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($objects as $object)
                            <tr>
                                <td>{{ @$object->folio }}</td>
                                <td>{{ @$object->date }}</td>
                                <td>{{ @$object->supplier }}</td>
                                <td>{{ @$object->product }}</td>
                                <td>{{ @$object->quantity }}</td>
                                <td>$ {{ number_format(@$object->unitPrice, 2, '.', ',') }}</td>
                                <td>$ {{ number_format(@$object->quantity * @$object->unitPrice, 2, '.', ',') }}</td>
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
            $("#form").attr('action', '{{ url('entries/products/export') }}')
            $("#form").submit();
            $("#form").attr('action', '{{ url('entries/products') }}')
        })
    </script>
@endsection
