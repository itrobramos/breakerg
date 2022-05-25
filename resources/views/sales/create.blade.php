@extends('layouts.business')


@section('content')
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

    </style>
    <div class="content-wrapper">

        <br>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">


                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">

                        <div class="row">

                            <div class="col-md-1 col-lg-1 col-sm-2 col-xs-2">

                                <button type="button" data-toggle="modal" data-target="#exampleModal"
                                    class="btn-md btn btn-primary m-btn m-btn--icon m-btn--pill"
                                    style="position: absolute; bottom:0;">
                                    <span>
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </button>
                            </div>

                            <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10" style="padding-left: 20px;">
                                <label for="exampleInputEmail1">Cliente</label>

                                <select name="clientId" class="form-control" id="cmbClientes" onchange="changeClient();">
                                    <option value="">Seleccione</option>
                                    @foreach ($clients as $c => $item)
                                        <option value="{{ $item->id }}" data-credit="{{ $item->credit }}"
                                            data-available="{{ $item->availableCredit }}" data-days="{{ $item->days }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                        <label for="exampleInputEmail1">Fecha Venta</label>
                        <input type="date" name="date" class="form-control">
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                        <label for="exampleInputEmail1">Folio</label>
                        <input type="number" name="folio" class="form-control">
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                        <label for="exampleInputEmail1">Imagen</label>
                        <input type="file" name="file" class="form-control">
                    </div>


                </div>

                <br><br><br>

                <div class="row">
                    <div class="col-3">
                        <h3>Agregar Producto</h3>
                    </div>

                    <div class="col-6">
                        <select id="cmbProducts" class="form-control select2">
                            <option value="">Seleccione</option>
                            @foreach ($products as $c => $item)
                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2">
                        <button type="button" onclick="addItemToEntry(this)"
                            class="btn-md btn btn-success m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="fa fa-check"></i>
                            </span>
                        </button>
                    </div>

                </div>

                <br><br>

                <table class="table table-bordered" id="tbl_parent_options">
                    <thead>
                        <tr class="bg-secondary color-palette">
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="rowsoptions">
                    </tbody>
                </table>

                <br>

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('entries.index') }}" class="btn btn-danger">Cancelar</a>
                        <button class="btn btn-success" id="btnCredito" style="display:none;" type="button"
                            data-toggle="modal" data-target="#modalCredito">Crédito</button>
                        <input type="submit" class="btn btn-success" name="paymentType" value="Contado"></button>
                    </div>
                </div>

                <div class="modal fade" id="modalCredito" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Pagar a crédito</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="Nombre">Crédito disponible</label>
                                    </div>

                                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                        <input type="text" id="txtCreditoDisponible"  class="form-control" readonly>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="Nombre">Plazo disponible</label>
                                    </div>

                                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                        <input type="text" id="txtDias" class="form-control" readonly>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="Nombre">Total venta</label>
                                    </div>

                                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                        <input type="text" id="txtTotal" name="TotalVenta" class="form-control" readonly>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="Nombre">Pago inicial</label>
                                    </div>

                                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                        <input type="text" id="txtPagoInicial" name="PagoInicial" class="form-control"
                                            onkeyup="calculateCredit()">
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="Nombre">A Crédito</label>
                                    </div>

                                    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                        <input type="text" id="txtACredito" class="form-control" name="montoCredito" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                <input type="submit" id="btnPagarCredito" class="btn btn-success" name="paymentType" value="Credito" disabled></button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>


        <div class="card-footer">

        </div>
    </div>


    {{-- Modals --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="Nombre">Nombre</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="text" id="txtName" name="name" class="form-control" required>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="Address">Dirección</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="text" name="address" id="txtAddress" class="form-control">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="Phone">Teléfono</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="text" name="phone" id="txtPhone" class="form-control">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="Email">Email</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="email" name="email" id="txtEmail" class="form-control">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="Person">Contacto</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="text" name="contact" id="txtContacto" class="form-control">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="rfc">RFC</label>
                        </div>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="text" name="rfc" id="txtRFC" class="form-control">
                        </div>
                    </div>

                    <br>

                    <div class="row pull-left">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="rfc">¿Cuenta con crédito?</label>
                        </div>

                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                            <input type="checkbox" name="credit" class="form-control" id="credit"
                                onchange="checkAmount();" id="chkCredit">
                        </div>
                    </div>

                    <br>

                    <div id="divMontoCredito" class="row" style="display: none;">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label for="rfc">Crédito otorgado</label>
                        </div>


                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <input type="number" name="creditAmount" id="creditAmount" step="any" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnCrear" onclick="addClient()" ; class="btn btn-success">Crear</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $('.select2').select2();

        function addItemToEntry(x) {
            var id = $("#cmbProducts").val();

            if (id == null || id == '')
                return;

            var productText = $("#cmbProducts option:selected").text();
            var price = $("#cmbProducts").find(':selected').data('price')

            var timestamp = Date.now();
            html = getTemplate(timestamp, productText, price, id);
            $("#rowsoptions").append(html);

            $("#cmbProducts option:selected").remove();

            calculateTotal();
        }

        function getTemplate(i, productText, price, productId) {
            var html = `
                <tr id="${i}" class="bg-light color-palette tdQty">
                    <td id="product${i}"
                    
                    return html;>${productText}
                        <input type="hidden" id="productText${i}" class="form-control"  value="${productText}">
                        <input type="hidden" id="productId${i}" class="form-control" name="product[${i}][productId]" value="${productId}">
                    </td>
                    <td id="product${i}">
                        <input type="number" step="any" class="form-control qtyProduct" name="product[${i}][quantity]"
                            id="quantity_${i}" required value="1" onchange="calculateTotal();">
                    </td>
                    <td>
                        <input type="number" step="any" class="form-control costProduct" name="product[${i}][unitPrice]"
                            id="extraCost_${i}" required value="${price}" onchange="calculateTotal();">
                    </td>
                    <td>`;

            html = html +
                `<button data-repeater-delete="" onclick="deletetemplate(${i})"
                            class="btn-md btn btn-danger m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="fa fa-trash"></i>
                            </span>
                        </button>
                    </td>
                </tr>
                `;

            return html;
        }


        function checkAmount() {
            if ($('#credit').is(':checked')) {
                $("#divMontoCredito").show();
            } else {
                $("#divMontoCredito").hide();
            }
        }

        function calculateTotal() {

            var total = 0;

            $('#rowsoptions').each(function(i, obj) {

                $(this).find(".tdQty").each(function() {
                    var id = $(this).attr('id');
                    var qtyProduct = $("#" + id).find('.qtyProduct').val();
                    var costProduct = $("#" + id).find('.costProduct').val();

                    total = total + (qtyProduct * costProduct);
                });

            });

            $("#txtTotal").val(total);



        }


        function calculateCredit() {
            var total = $("#txtTotal").val();
            var pagoInicial = $("#txtPagoInicial").val();

            $("#txtACredito").val(total - pagoInicial);

            var CreditoDisponible = $("#txtCreditoDisponible").val();

            if (CreditoDisponible >= total - pagoInicial) {
                $("#btnPagarCredito").prop("disabled", false);
            } else {
                $("#btnPagarCredito").prop("disabled", true);
            }
        }

        function deletetemplate(i) {
            var RecoveredId = $("#productId" + i).val();
            var RecoveredText = $("#productText" + i).val();

            var o = new Option(RecoveredText, RecoveredId);
            $(o).html(RecoveredText);
            $("#cmbProducts").append(o);

            $("#" + i).remove();
            $(".collapse" + i).remove();

            calculateTotal();
        }

        function deletesimpletemplate(i) {
            $("#" + i).remove();

            calculateTotal();
        }

        function changeClient() {
            var credit = $("#cmbClientes").find(':selected').data('credit');
            var available = $("#cmbClientes").find(':selected').data('available');
            var dias = $("#cmbClientes").find(':selected').data('days');

            if (available == null || available == "")
                available = 0;

            $("#txtCreditoDisponible").val(available);
            $("#txtDias").val(dias);

            if (credit) {
                $("#btnCredito").show();
            } else {
                $("#btnCredito").hide();
            }

        }

        function pagarCredito() {

        }

        function addClient() {
            var name = $("#txtName").val();
            var address = $("#txtAddress").val();
            var phone = $("#txtPhone").val();
            var email = $("#txtEmail").val();
            var contact = $("#txtContacto").val();
            var rfc = $("#txtRFC").val();
            var creditAmount = $("#creditAmount").val();

            if ($('#credit').is(':checked')) {
                var credit = 1;
            } else {
                var credit = 0;
            }

            $("#loader").addClass("is-active");
            $.ajax({
                type: "POST",
                url: "/clients/storeAjax",
                data: {
                    "_token": "{{ csrf_token() }}",
                    name,
                    address,
                    phone,
                    email,
                    contact,
                    rfc,
                    credit,
                    creditAmount
                },
                dataType: 'json',
                success: function(data) {

                    $.toast({
                        heading: 'Cliente Guardado',
                        text: 'Cliente guardado correctamente.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 1500
                    });
                    $("#loader").removeClass("is-active");

                    var html = "<option value = " + data.id + " data-credit=" + data.credit + "> " + data.name +
                        " </option>";
                    $("#cmbClientes").append(html);

                    $('#exampleModal').modal('hide');

                },
                error: function() {
                    $.toast({
                        heading: 'Error al guardar cliente',
                        text: 'Un error ocurrió.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 1500
                    });
                }
            });
        }
    </script>
@endsection
