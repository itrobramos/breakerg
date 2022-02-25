@extends('layouts.business')


@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Cambiar contraseña</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Cambiar contraseña</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="d-flex justify-content-center">
            <!-- /.card-header -->

            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Cambiar contraseña {{Auth::user()->name}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                    <form action="{{ url('/users/updatePassword') }}" method="post" >
                        {{ csrf_field()}}
                        <br>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nueva Contraseña (6 caracteres mínimo)</label>
                                    <input type="password" name="password" class="form-control" id="password1" onkeyup="validatePassword();">
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Repetir contraseña</label>
                                    <input type="password" name="password2" class="form-control" id="password2" onkeyup="validatePassword();">
                                </div>
                            </div>
                        </div>
            
                      
                        <div class="row">
                            <div class="col-12">
                                <a href="" class="btn btn-danger">Cancelar</a>
                                <button class="btn btn-primary" style="display:none;" id="btnGuardar" type="submit">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- /.card-body -->
        </div>



    </div>




    <script>
        $(function(){
            $('.select2').select2();
        })

        function validatePassword(){

            var password1 = $("#password1").val();
            var password2 = $("#password2").val();
            
            if(password1 == password2 && password1.length >= 6){
                $("#btnGuardar").show();
            }
            else{
                $("#btnGuardar").hide();
            }
        }

    </script>
@endsection
