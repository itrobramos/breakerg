@extends('layouts.business')


@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Usuarios del sistema</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Usuarios del sistema</li>
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
                    <a href="{{ url('users/add') }}">
                        <button type="button" class="btn btn-success">Agregar</button>
                    </a>
                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Administrador</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td style="text-align: center;">@if ($user->admin == true) <i class="fas fa-check-square fa-2x" style="color:green"></i> @endif</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('users.edit', ['id' => $user->id]) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Editar
                                    </a>

                                    <a class="btn btn-danger btn-sm button-destroy"
                                        href="{{ route('users.destroy', ['id' => $user->id]) }}"
                                        data-original-title="Eliminar" data-method="get"
                                        data-trans-button-cancel="Cancelar" data-trans-button-confirm="Eliminar"
                                        data-trans-title="¿Está seguro de esta operación?"
                                        data-trans-subtitle="Esta operación eliminará este registro permanentemente">
                                        <i class="fas fa-trash">
                                        </i>
                                        Eliminar
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
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>

@endsection
