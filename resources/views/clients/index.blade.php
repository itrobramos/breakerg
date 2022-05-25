@extends('layouts.business')


@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Clientes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Clientes</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="card">

            <div class="card-header">
                <div class="row justify-content-between" style="padding-right:20px;">
                    <a href="{{ url('home') }}">
                        <button type="button" class="btn btn-danger">Regresar</button>
                    </a>
                    <a href="{{ url('clients/add') }}">
                        <button type="button" class="btn btn-success">Agregar</button>
                    </a>

                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Email</th>
                            <th>Límite crédito</th>
                            <th>Debe</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->contact }}</td>
                                <td>{{ $client->email }}</td>
                                <td>${{ $client->creditAmount }}</td>
                                <td>${{ $client->creditAmount - $client->availableCredit }}</td>

                                <td>

                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('clients.edit', ['id' => $client->id]) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Editar
                                    </a>

                                    <a class="btn btn-danger btn-sm button-destroy"
                                        href="{{ route('clients.destroy', ['id' => $client->id]) }}"
                                        data-original-title="Eliminar" data-method="get" data-trans-button-cancel="Cancelar"
                                        data-trans-button-confirm="Eliminar"
                                        data-trans-title="¿Está seguro de esta operación?"
                                        data-trans-subtitle="Esta operación eliminará este registro permanentemente">
                                        <i class="fas fa-trash">
                                        </i>
                                        Eliminar
                                    </a>

                                    @if ($client->credit)
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('clients.pay', ['id' => $client->id]) }}">
                                            <i class="fa fa-money-bill"></i>
                                            Historial Crédito
                                        </a>
                                    @endif

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
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
