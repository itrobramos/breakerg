@extends('layouts.business')


@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Realizar Abono : {{ $client->name }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Realizar abono</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="d-flex justify-content-center">
            <!-- /.card-header -->

            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">

                        <div class="d-flex">
                            <a href="{{ url('clients') }}">
                                <button type="button" class="btn btn-danger p-2">Regresar</button></a>
                            <button type="submit" class="btn btn-success ml-auto p-2" data-toggle="modal"
                                data-target="#modalAbono">Realizar Abono</button>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>${{ $client->creditAmount }}</h3>
                                        <p>Crédito otorgado</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>${{ $client->availableCredit }}</h3>
                                        <p>Crédito disponible</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>${{ $client->creditAmount - $client->availableCredit }}</h3>
                                        <p>Pendiente pago</p>
                                    </div>
                                </div>
                            </div>

                            @php
                                $vnextDate = new DateTime($nextDate);
                                
                            @endphp
                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        @if($client->creditAmount - $client->availableCredit == 0)
                                        <h3>N/A</h3>
                                        <p>Sin pagos pendientes</p>
                                        @else
                                        <h3>{{ $vnextDate->format('d-m-Y') }}</h3>
                                        <p>Próximo vencimiento</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <table class="table table-hover">
                                <tr class="bg-dark">
                                    <td>Fecha</td>
                                    <td>Cargo</td>
                                    <td>Abono</td>
                                    <td>Saldo</td>
                                </tr>

                                {{-- @foreach ($Credits as $credit)
                                    <tr>
                                        <td>{{ $credit->beginDate }}</td>
                                        <td>{{ $credit->credit }}</td>
                                        <td></td>
                                        <td>${{ $credit->currentCredit }}</td>
                                    </tr>
                                @endforeach --}}

                                @foreach ($Movements as $movement)
                                    @php
                                        
                                        $date = new DateTime($movement->created_at);
                                        
                                    @endphp
                                    <tr>
                                        <td>{{ $date->format('d-m-Y') }}</td>
                                        @if ($movement->type == 1)
                                            <td></td>
                                            <td>{{ $movement->payment }}</td>
                                        @else
                                            <td>
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('sales.show', ['id' => $movement->saleId ]) }}">
                                                    <i class="fas fa-eye">
                                                    </i>
                                                    Detalles
                                                </a>
                                                {{ $movement->payment }}
                                            </td>
                                            <td></td>
                                        @endif
                                        <td>${{ $movement->newDebt }}</td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>

                    </div>
                    <!-- /.card-body -->

                </div>


            </div>
        </div>

        <!-- /.card-body -->
    </div>

    </div>

    {{-- Modals --}}

    <div class="modal fade" id="modalAbono" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{ url('/payment/store') }}">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="Nombre">Abono</label>
                            </div>

                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <input type="hidden" name="clientId" value="{{ $client->id }}">
                                <input type="number" step="any" id="txtAbono" name="Abono" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnCrear" class="btn btn-success">Recibir Abono</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

<script>
    function checkAmount() {
        if ($('#chkCredit').is(':checked')) {
            $(".divMontoCredito").show();
        } else {
            $(".divMontoCredito").hide();
        }
    }
</script>
<
