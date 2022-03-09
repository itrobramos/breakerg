@extends('layouts.business')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Productos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Productos</li>
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

                <form action="{{ url('reports/inventary') }}" method="POST" id="form">
                    <div class="row">
    
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="txtProducto" name="Product" placeholder="Producto"
                                value="{{ isset($Parameters['Product']) ? $Parameters['Product'] : '' }}">
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-success btn-md" type="submit">Buscar</button>
                            <button class="btn btn-dark btn-md" id="btnExportar" type="button">Exportar</button>
                        </div>
    
                    </div>
                </form>
    
                <br>

                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($objects as $object)
                            <tr>
                                <td>{{ $object->name }}</td>
                                <td>{{ $object->stock }}</td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });

        $('#btnExportar').click(function(e){
            $("#form").attr('action','{{ url('reports/inventary/export') }}')
            $("#form").submit();
            $("#form").attr('action','{{ url('reports/inventary') }}')
        })

    </script>

@endsection
