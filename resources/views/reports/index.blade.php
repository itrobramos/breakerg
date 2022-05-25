@extends('layouts.business')

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Reportes</h1>
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

        <div class="card">

            <div class="row">

                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Flujo Efectivo
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('reports/cashflow')}}">
                                <img src="images/presentation.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>
    
                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Ventas
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('sales')}}">
                                <img src="images/platform.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Ventas detallado
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('sales/products')}}">
                                <img src="images/platform.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>
    
                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Compras
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('entries')}}">
                                <img src="images/purchase.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Compras detallado
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('entries/products')}}">
                                <img src="images/purchase.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Inventario
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('reports/inventary')}}">
                                <img src="images/inventario.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Clientes con crédito activo
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('reports/activecredits')}}">
                                <img src="images/credit.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                    <div class="card card-row card-default">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                Pagos parciales / Abonos
                            </h3>
                        </div>
                        <div class="card-body" id="body_nuevos">
                            <a href="{{url('reports/partialpayments')}}">
                                <img src="images/payment.png" alt="" class="" style="object-fit: cover;width:250px;height:250px">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
         
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>

@endsection


