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


        <div class="card-body">
            <form action="{{ route('entries.store') }}" method="post" enctype="multipart/form-data" autocomplete="off"> 


                <div class="row">
                    <div class="col-md-6 col-lg-3 col-sm-6 col-xs-6">
                        <label for="exampleInputEmail1">Proveedor</label>
                        <select name="supplierId" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach ($suppliers as $c => $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-lg-3 col-sm-6 col-xs-6">
                        <label for="exampleInputEmail1">Fecha Entrada</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                        <label for="exampleInputEmail1">Folio</label>
                        <input type="number" name="folio" min="0" class="form-control">
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
                                <option value="{{ $item->id }}">
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
                        <button type="button" data-toggle="modal" data-target="#exampleModal"
                            class="btn-md btn btn-primary m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="fa fa-plus"></i>
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
                        <button class="btn btn-primary" type="submit">Guardar</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Agregar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{ url('/products/store') }}">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="Nombre">Nombre</label>
                            </div>

                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <input type="text" id="txtName" name="name" class="form-control" required>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="desciption">Descripción</label>
                            </div>

                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <textarea class="form-control" id="txtDescription" name="description"></textarea>
                            </div>
                        </div>

                        <br>

                        {{-- <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="Stock">Stock</label>
                            </div>

                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="stock" id="txtStock" class="form-control" required>
                            </div>
                        </div> --}}

                        <br>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="price">Precio</label>
                            </div>

                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="price" id="txtPrice" class="form-control" required>
                            </div>
                        </div>

                        <br>


                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="Stock">Tipo Producto</label>
                            </div>
                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <select name="productTypeId" id="cmbType" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($types as $c => $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnCrear" onclick="addProduct()" ; class="btn btn-success">Crear</button>
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

            var timestamp = Date.now();
            html = getTemplate(timestamp, productText, id);
            $("#rowsoptions").append(html);

            $("#cmbProducts option:selected").remove();
        }

        function getTemplate(i, productText, productId) {
            var html = `
                <tr id="${i}" class="bg-light color-palette">
                    <td id="product${i}"
                    
                    return html;>${productText}
                        <input type="hidden" id="productText${i}" class="form-control"  value="${productText}">
                        <input type="hidden" id="productId${i}" class="form-control" name="product[${i}][productId]" value="${productId}">
                    </td>
                    <td id="product${i}">
                        <input type="number" step="any" class="form-control" name="product[${i}][quantity]"
                            id="quantity_${i}" required value="1">
                    </td>
                    <td>
                        <input type="number" step="any" class="form-control" name="product[${i}][unitPrice]"
                            id="extraCost_${i}" required value="0">
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

        function deletetemplate(i) {
            var RecoveredId = $("#productId" + i).val();
            var RecoveredText = $("#productText" + i).val();

            var o = new Option(RecoveredText, RecoveredId);
            $(o).html(RecoveredText);
            $("#cmbProducts").append(o);

            $("#" + i).remove();
            $(".collapse" + i).remove();
        }

        function deletesimpletemplate(i) {
            $("#" + i).remove();
        }


        function addProduct() {
            var name = $("#txtName").val();
            var description = $("#txtDescription").val();
            var stock = 0;
            var productTypeId = $('#cmbType').val();
            var price = $("#txtPrice").val();

            $("#loader").addClass("is-active");
            $.ajax({
                type: "POST",
                url: "/products/storeAjax",
                data: {
                    "_token": "{{ csrf_token() }}",
                    name,
                    description,
                    stock,
                    productTypeId,
                    price
                },
                dataType: 'json',
                success: function(data) {

                    $.toast({
                        heading: 'Producto Guardado',
                        text: 'Producto guardado correctamente.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 1500
                    });
                    $("#loader").removeClass("is-active");

                    var html = "<option value = " + data.id + "> " + data.name + " </option>";
                    $("#cmbProducts").append(html);

                    $('#exampleModal').modal('hide');

                },
                error: function() {
                    $.toast({
                        heading: 'Error al guardar venta',
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
