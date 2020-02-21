@extends('layouts.customer.app')

@section("title")
    Inventory | Quick Create
@endsection
@push("css")
    <style>
        .hide{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Inventory | Quick Create</h3>
                </div>
                <div>
                    <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                        <span class="m-subheader__daterange-label">
										<span class="m-subheader__daterange-title"></span>
										<span class="m-subheader__daterange-date m--font-brand"></span>
                        </span>
                        <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
										<i class="la la-angle-down"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">

                    <div class="row">
                        <div class="col-md-2">
                            <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                <button type="submit" class="btn btn-warning">Reset all</button>
                            </form>
                        </div>
                        <div class="col-md-10">
                            <form action="{{url()->current()}}" method="post" class="form-inline" role="form">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" required onchange="getColorByBrandPlusModel()">
                                        <option value="" selected> models </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="color" id="selected_color" required onchange="getStorageByBrandPlusModelColor()">
                                        <option value="" selected> Color </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="storage" id="selected_storage" required onchange="getLotByBrandPlusModelColorStorage()">
                                        <option value="" selected> Storage </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="lot_id" id="selected_lot" required onchange="getlotAsinByBrandPlusModelColorStorage()">
                                        <option value="" selected> Lot </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    {{--<button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>


                    <form class="form-horizontal" id="inventory_insert_form" role="form">
                        <div class="row">
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" disabled="" name="brand" value="" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" disabled name="model"value="" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <input type="text" class="form-control"disabled name="network" id=""  value="" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">ASIN</label>
                                    <select class="form-control" name="asin" id="selected_asin" required onchange="getAsinQuantityByAsin()"  >
                                        <option value="" selected></option>
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Quantity</label>
                                    <input type="number" class="form-control" disabled  name="quantity" id=""  value="" required >
                                    <div class="update_asin_qty_when_zero hide">
                                        <label for="pwd">Add more quantity in same asin</label>
                                        <input type="number" class="form-control" name="updated_qty" value="" >
                                        <button type="button" class="btn btn-primary btn-sm" onclick="update_asin_quantity()">Update</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Lot_ID</label>
                                    {{--<input type="text" class="form-control" name="lot_id" id="lot_id_val" onchange="getLot()"  value="" required>--}}
                                    <select class="form-control m-select2" id="m_select2_1" name="lot_id_form" required disabled >
                                        <option value="" selected></option>
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" name="color_form" value="" disabled>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <select class="form-control m-select2" id="m_select2_1" name="storage_form" required disabled >
                                        <option value="" selected></option>
                                    </select>
                                </div>

                                <div class="form-group margin-0">
                                    <label for="usr">Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="" selected></option>
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group margin-0 on_error">
                                    <label for="usr">IMEI</label>
                                    <input type="text" class="form-control" name="imei" id="" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" minlength="14" value=""  required>
                                    <small id="imei_exist" class="text-danger"></small>
                                </div>

                                <div class="form-group margin-0">


                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">save inventory or add more IMEI</label>
                                    <button type="submit" class="btn btn-warning" style="width: 100%">Add More Inventory</button>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-top: 1%;">
                                <span  style="color: blue" id="imei_success"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation m--font-brand"></i>
            </div>
            <div class="m-alert__text">
                DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.
                For more info see
            </div>
        </div>

    </div>
@stop
@push('scripts')
    <script src="{{asset('customer/assets/demo/default/custom/crud/forms/widgets/select2.js')}}" type="text/javascript"></script>
    <script>
        //delete wrongly added imei
        function delete_wrongly_added_imei(imei){
            $.ajax({
                type: "get",
                url: '{{route("inventory.delete.by_imei")}}?imei='+imei,
                success: function (data) {
                    console.log(data)
                    $('#'+imei).text('cancelled')
                   var qty = $('input[name=quantity]').val()
                    qty = +qty + +1;
                    $('input[name=quantity]').val(qty)
                }
            });
        }

        function update_asin_quantity(){
            if ($('input[name=updated_qty]').val() > 0) {
                var lot_id = $('#m_select2_1').val();
                var color = $('select[name=color]').val();
                var storage_id = $('select[name=storage]').val();
                var asin = $('select[name=asin]').val();
                var updated_quantity = $('input[name=updated_qty]').val();
                console.log(color + storage_id)

                $.ajax({
                    type: "GET",
                    url: '{{route("update_asin_quantity")}}/' + lot_id + '?color=' + color + '&storage_id=' + storage_id + '&asin=' + asin+ '&updated_quantity=' + updated_quantity,
                    success: function (data) {
                        // console.log(data + 'working')
                        $('input[name=updated_qty]').val('')
                        $('input[name=quantity]').val(data)
                        $('.update_asin_qty_when_zero').addClass('hide')
                    }
                })
            }
        }


        $('form#inventory_insert_form').on('submit', function(e) {
            e.preventDefault();
            var less_than = $('input[name=imei]').val();
            if(less_than.length < 14){
                alert('you entered less than 14 numbers ')
                return false
            }
            else {
                var city = 'null';
                if (!$('input[name=quantity]').val() || $('input[name=quantity]').val() == 0) {
                    alert('please you cannot enter more inventory in same asin! asin quantity is full')
                    $('.update_asin_qty_when_zero').removeClass('hide')
                    return false
                }
                else {
                    console.log($('select[name=lot_id_form]').val())
                    console.log( $('input[name=color_form]').val())
                    console.log($('select[name=storage_form]').val())
                    console.log($('select[name=asin]').val())
                    console.log($('select[name=category]').val())
                    console.log($('input[name=imei]').val())

                    $.ajax({
                        url: '{{route('inventory.store')}}',
                        dataType: 'json',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            lot_id: $('select[name=lot_id_form]').val(),
                            color: $('input[name=color_form]').val(),
                            storage_id: $('select[name=storage_form]').val(),
                            asin: $('select[name=asin]').val(),
                            category_id: $('select[name=category]').val(),
                            imei: $('input[name=imei]').val(),
                        },
                        success: function (data, textStatus, jQxhr) {
                            console.log(data+'kdsklfks')
                            if (data == 123456789) {
                                $('.on_error').addClass('has-error')
                                $('#imei_exist').html("Imei already exist! you can't put it again")
                            }
                            else {
                                $('.on_error').removeClass('has-error')
                                $('#imei_exist').html("")
                                var imei = $('input[name=imei]').val()
                                $('#imei_success').append('<br><span id=' + imei + '>' + imei + '</span> <a onclick="delete_wrongly_added_imei(\'' + imei + '\')" style="cursor: pointer;color: red;">&nbsp;cancel</a>')
                                $('input[name=imei]').val('')
                                if (data < 1) {
                                    $('input[name=quantity]').val('')
                                    $('.update_asin_qty_when_zero').removeClass('hide')
                                }
                                else {
                                    $('input[name=quantity]').val(data)
                                }
                            }
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    })
                }
            }
        })





        ////////////////////////////////
        function getModelByBrand() {
            var brand_id =  $('select[name=brand_id]').val();
            var brand =  $('select[name=brand_id] :selected').text();
             $('input[name=brand]').val(brand);
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id
                },
                url: "{{route('model_by_brand')}}",
                success: function (data) {
                    // console.log(data)
                    $('select[name=model]').html('<option value="" selected> model </option>')
                    $.each(data, function (index, value) {
                        $('select[name=model]').append('<option value=' + value.model + '>' + value.model + '</option>')
                    });
                }
            });
        }


        // get color by brands+modal
        function getColorByBrandPlusModel() {
            var brand_id =  $('select[name=brand_id]').val();
            var model =  $('select[name=model]').val();
            $('input[name=model]').val(model);
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model,
                },
                url: "{{route('color_by_brand_plus_model')}}",
                success: function (data) {
                    $('select[name=color]').html('<option value="" selected> Color </option>')
                    $.each(data, function (index, value) {
                        $('select[name=color]').append('<option value=' + value.color + '>' + value.color + '</option>')
                    });
                }
            });
        }
        // get storage by brands+modal+color
        function getStorageByBrandPlusModelColor() {
            var brand_id =  $('select[name=brand_id]').val();
            var model =  $('select[name=model]').val();
            var color =  $('select[name=color] :selected').text();
            console.log(color)
            $('input[name=color_form]').val(color);
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model,
                    "color": color,
                },
                url: "{{route('storage_by_brand_plus_model_color')}}",
                success: function (data) {
                    $('select[name=storage]').html('<option value="" selected> Storage </option>')
                    $.each(data, function (index, value) {
                        $('select[name=storage]').append('<option value=' + value.storage_id + '>' + value.storage_name + '</option>')
                    });
                }
            });
        }
        // get Lot by brands+modal+color+storage
        function getLotByBrandPlusModelColorStorage() {
            var brand_id =  $('select[name=brand_id]').val();
            var model =  $('select[name=model]').val();
            var color =  $('select[name=color]').val();
            var storage_id =  $('select[name=storage]').val();

            var storage_val =  $('select[name=storage]').val();
            var storage_text =  $('select[name=storage] :selected').text();
            $('select[name=storage_form]').html('<option selected value=' + storage_val + '>' + storage_text + '</option>')

            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model,
                    "color": color,
                    "storage_id": storage_id,
                },
                url: "{{route('lot_by_brand_plus_model_color_storage')}}",
                success: function (data) {
                    console.log(data)
                    $('select[name=lot_id]').html('<option value="" selected> Lot </option>')
                    $.each(data, function (index, value) {
                        $('select[name=lot_id]').append('<option value=' + value.lot_id + '>' + value.lot_id + '</option>')
                    });
                }
            });
        }

        // get Lot by brands+modal+color+storage
        function getlotAsinByBrandPlusModelColorStorage() {
            var brand_id =  $('select[name=brand_id]').val();
            var model =  $('select[name=model]').val();
            var color =  $('select[name=color]').val();
            var storage_id =  $('select[name=storage]').val();
            var lot_id =  $('select[name=lot_id]').val();
            var lot_id_text =  $('select[name=lot_id] :selected').text();

            $('select[name=lot_id_form]').html('<option selected value=' + lot_id_text + '>' + lot_id_text + '</option>')

            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model,
                    "color": color,
                    "storage_id": storage_id,
                    "lot_id": lot_id,
                },
                url: "{{route('lot_asin_by_brand_plus_model_color_storage')}}",
                success: function (data) {
                    console.log(data)
                    $('select[name=asin]').html('<option value="" selected> Asin </option>')
                    $.each(data, function (index, value) {
                        $('input[name=network]').val(value.network)
                        $('select[name=asin]').append('<option value=' + value.asin + '>' + value.asin + '</option>')
                    });
                }
            });
        }

        function getAsinQuantityByAsin(){
            $('input[name=quantity]').val('')

            var lot_id = $('select[name=lot_id]').val();
            var color = $('select[name=color]').val();
            var storage_id = $('select[name=storage]').val();
            var asin = $('select[name=asin]').val();
            console.log(color + storage_id)

            $.ajax({
                type: "GET",
                url: '{{route("get_asin_by_storage")}}/'+lot_id+'?color='+color+'&storage_id='+storage_id+'&asin='+asin,
                success: function (data) {
                    console.log(data+ 'working')
                    $.each(data, function( index, value ) {
                        $('input[name=quantity]').val(value.asin_total_quantity - value.inventory_quantity)
                        if($('input[name=quantity]').val() == 0){
                            $('.update_asin_qty_when_zero').removeClass('hide')

                        }
                    });
                }
            })
        }

    </script>
@endpush
