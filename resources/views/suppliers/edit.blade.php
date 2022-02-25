@extends('layouts.business')


@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Editar Proveedor</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Editar Proveedor</li>
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
                        <h3 class="card-title">Editar {{$supplier->name}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                    <form role="form" method="Post" action="{{ url('/suppliers/edit/' .$supplier->id) }}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        {{ method_field('PATCH')}}
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Nombre">Nombre</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="name" class="form-control" value="{{$supplier->name}}">
                                </div>                                
                            </div>

                            <br>
          
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Address">Dirección</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="address" class="form-control" value="{{$supplier->address}}">
                                </div>                                
                            </div>

                            <br>
          
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Phone">Teléfono</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="phone" class="form-control" value="{{$supplier->phone}}">
                                </div>                                
                            </div>
                            <br>
          
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Email">Email</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="email" name="email" class="form-control" value="{{$supplier->email}}">
                                </div>                                
                            </div>

                            <br>
          
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Person">Contacto</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="contact" class="form-control" value="{{$supplier->contact}}">
                                </div>                                
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="rfc">RFC</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="rfc" class="form-control" value="{{$supplier->rfc}}">
                                </div>                                
                            </div>



                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer d-flex">
                            <a href="{{url('suppliers')}}"><button type="button" class="btn btn-danger p-2">Regresar</button></a>
                            <button type="submit" class="btn btn-success ml-auto p-2">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.card-body -->
        </div>
    </div>




@endsection
