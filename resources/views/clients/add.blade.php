@extends('layouts.business')


@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Agregar Cliente</h1>
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

        <div class="d-flex justify-content-center">
            <!-- /.card-header -->

            <div class="col-md-12">
                <div class="card card-primary">
                    <!-- form start -->

                    <form role="form" method="POST" action="{{ url('/clients/store') }}" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="card-body">


                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Nombre">Nombre</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Address">Dirección</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="address" class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Phone">Teléfono</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="phone" class="form-control">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Email">Email</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="Person">Contacto</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="contact" class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="rfc">RFC</label>
                                </div>

                                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                    <input type="text" name="rfc" class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row pull-left">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label for="rfc">¿Cuenta con crédito?</label>
                                </div>

                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                                    <input type="checkbox" name="credit" class="form-control" onchange="checkAmount()" id="chkCredit">
                                </div>
                            </div>

                            <br>

                            <div id="divMontoCredito" class="row" style="display: none;">
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="rfc">Crédito otorgado</label>
                                </div>


                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-3">
                                    <input type="number" name="creditAmount" step="any" class="form-control">
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer d-flex">
                            <a href="{{ url('clients') }}"><button type="button"
                                    class="btn btn-danger p-2">Regresar</button></a>
                            <button type="submit" class="btn btn-success ml-auto p-2">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- /.card-body -->
        </div>

    </div>
@endsection

<script>
    function checkAmount() {
        if($('#chkCredit').is(':checked')){
            $("#divMontoCredito").show();
        }
        else{
            $("#divMontoCredito").hide();
        }
    }
</script>
