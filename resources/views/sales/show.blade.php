@extends('layouts.business')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Detalle de venta</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('sales') }}">Detalle de venta</a></li>
                            <li class="breadcrumb-item active"><a href="#">{{ @$object->folio }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="row">
                <div class="col-12">
                    <div class="invoice p-3 mb-3">

                        <div class="row invoice-info">
                            <div class="col-sm-2 invoice-col">
                                <img src="{{ env('DEPLOY_URL') }}/dist/img/logo.jpg" style="height: 150px;">
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-3 invoice-col">
                                <b>Cliente:</b> {{ @$object->client->name }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Fecha:</b> {{ @$object->date }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Total:</b> $ {{ number_format($object->total, 2, '.', ',') }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Folio #{{ @$object->folio }}</b>
                            </div>
                        </div>
                    

                        <br>


                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Producto</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($details as $detail)
                                            <tr>
                                                <td>{{ @$detail->quantity }}</td>
                                                <td>{{ @$detail->product->name }}</td>
                                                <td>${{ number_format(@$detail->price, 2, '.', ',') }}</td>
                                                <td>${{ number_format($detail->price * $detail->quantity, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-8">
                            </div>
                            <div class="col-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Total:</th>
                                                <td>$
                                                    {{ number_format(@$object->total, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row no-print">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary float-right"
                                    onclick="window.print();return false;" style="margin-right: 5px;">
                                    <i class="fas fa-download"></i> Imprimir / Guardar PDF
                                </button>

                                <a href="{{ url()->previous() }}"> <button type="button"
                                        class="btn btn-danger float-right" style="margin-right: 5px;">
                                        Regresar
                                    </button>
                                </a>

                            </div>
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
