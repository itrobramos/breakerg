@extends('layouts.business')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Entradas de mercancía</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('entries') }}">Entradas de mercancía</a></li>
                            <li class="breadcrumb-item active"><a href="#">{{$object->id}}</a></li>
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
                                <img src="{{env('DEPLOY_URL')}}/dist/img/logo.jpg" style="height: 150px;">
                            </div>
                        </div>
                        
                        <div class="row invoice-info">
                            <div class="col-sm-3 invoice-col">
                                <b>Proveedor:</b> {{ @$object->supplier->name }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Fecha Entrada:</b> {{ @$object->date }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Total:</b> {{ $object->totalCost }}
                            </div>
                            <div class="col-sm-3 invoice-col">
                                <b>Folio #{{ $object->id }}</b>
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
                                                <td>${{ @$detail->unitPrice }}</td>
                                                <td>${{ number_format((float) $detail->unitPrice * $detail->quantity, 2, '.', '') }}
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
                                                    {{ number_format((float) @$object->totalCost + $object->shipCost, 2, '.', '') }}
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
