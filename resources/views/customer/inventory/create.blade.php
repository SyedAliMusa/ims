@extends('layouts.customer.app')

@section("title")
    Inventory | Create
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
                    <h3 class="m-subheader__title ">Inventory | Create</h3>
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
                    <form class="form-horizontal" id="inventory_insert_form" role="form">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-6 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Lot_ID</label>
                                    {{--<input type="text" class="form-control" name="lot_id" id="lot_id_val" onchange="getLot()"  value="" required>--}}
                                    <select class="form-control m-select2" id="m_select2_1" name="lot_id" required onchange="getLot()">
                                        <option value="" selected></option>
                                        @foreach($lots as $item)
                                            <option value="{{$item->lot_id}}">{{$item->lot_id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <select class="form-control" name="brand" id="brand_id" onchange="getLotModel()"  required>
                                        <option value="" selected>Select</option>
                                    </select>
                                    {{--<input type="text" class="form-control" disabled="" name="brand" value="" required>--}}
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <select class="form-control" name="model" id="model_id" onchange="getLotNetwork()" required>
                                        <option value="" selected>Select</option>
                                    </select>
                                    {{--<input type="text" class="form-control" disabled name="model"value="" required>--}}
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <select class="form-control" name="color" id="selected_color" required onchange="getStorageByColor()">
                                        <option value="" selected>Select</option>
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <div id = 'checkingnetwork'>
                                        <input type="text" class="form-control" disabled name="network" id=""  value="" required>
                                    </div>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <select class="form-control" name="storage" id="selected_storage" required onchange="getAsinByStorage()">
                                        <option value="" selected>Select</option>
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">ASIN</label>
                                    {{-- <select class="form-control" name="brand"  required>
                                         <option value="" selected></option>
                                     </select>--}}
                                    <select class="form-control" name="asin" id="selected_asin" required onchange="getAsinQuantityByAsin()" >
                                        <option value="" selected>Select</option>
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="" selected>Select</option>
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
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                <label for="pwd">Quantity</label>
                                                <input type="number" class="form-control" disabled  name="quantity" id="qty"  value="" required>
                                            </td>
                                            <td>
                                                <div class="update_asin_qty_when_zero hide">
                                                    <br>
                                                <label for="pwd">Add more quantity in same asin</label>
                                                <input type="number" class="form-control" name="updated_qty" value="" >
                                                <button type="button" class="btn btn-primary btn-sm" onclick="update_asin_quantity()">Update</button>
                                            </div>
                                            </td>
                                        </tr>
                                    </table>
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
        function getLot(){ // Get All Brands against this lot id
            $('select[name=brand]').children('option:not(:first)').remove();
            $('select[name=model]').children('option:not(:first)').remove();
            $('select[name=asin]').children('option:not(:first)').remove();
            $('select[name=color]').children('option:not(:first)').remove();
            $('select[name=storage]').children('option:not(:first)').remove();
            $('select[name=network]').children('option:not(:first)').remove()
            $('select[name=network]').hide()
            $('input[name=network]').show()
            $('input[name=network]').val('')
            $('input[name=quantity]').val('')


            var lot_id = $('#m_select2_1').val();
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_lot_id")}}/'+lot_id,
                success: function (data) {
                    $.each(data['brand'], function( index, value ) {
                        console.log(value)
                        $('select[name=brand]').append('<option value='+value+'>'+value+'</option>')
                    });

                    /* $('select[name=brand]').val(data['brand'])
                     $('input[name=model]').val(data['model'])
                     $('input[name=network]').val(data['network'])
                     $.each(data['lot'], function( index, value ) {
                         $('select[name=color]').append('<option value='+value.color+'>'+value.color+'</option>')
                         // $('select[name=storage]').append('<option value='+value.storage_id+'>'+value.storage+'</option>')
                         // $('select[name=asin]').append('<option value='+value.asin+'>'+value.asin+'</option>')
                     });*/
                }
            })
        }
        function getLotModel(){
            $('select[name=model]').children('option:not(:first)').remove();
            $('select[name=asin]').children('option:not(:first)').remove();
            $('select[name=color]').children('option:not(:first)').remove();
            $('select[name=storage]').children('option:not(:first)').remove();
            $('select[name=network]').children('option:not(:first)').remove()
            $('select[name=network]').hide()
            $('input[name=network]').show()
            $('input[name=network]').val('')
            $('input[name=quantity]').val('')

            var lot_id = $('#m_select2_1').val()
            var lot_brand = $('#brand_id').val()
            console.log('Lot_id: '+lot_id+' Lot_brand: '+lot_brand)
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_lot_brand")}}/'+lot_id+'?lot_brand='+lot_brand,
                success: function (data) {
                    $.each(data['model'], function( index, value ) {
                        console.log('Model'+value)
                        $('select[name=model]').append('<option value='+value+'>'+value+'</option>')
                    });

                }
            })
        }

        function getLotNetwork(){
            $('select[name=asin]').children('option:not(:first)').remove();
            $('select[name=color]').children('option:not(:first)').remove();
            $('select[name=storage]').children('option:not(:first)').remove();
            $('select[name=network]').children('option:not(:first)').remove()
            $('select[name=network]').hide()
            $('input[name=network]').show()
            $('input[name=network]').val('')
            $('input[name=quantity]').val('')

            var lot_id = $('#m_select2_1').val()
            var lot_brand = $('#brand_id').val()
            var lot_model = $('#model_id').val()
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_lot_model")}}/'+lot_id+'?lot_brand='+lot_brand+'&lot_model='+lot_model,
                success: function (data) {
                    // $('input[name=network]').val(data['network'])
                    $.each(data['color'], function( index, value ) {
                        $('select[name=color]').append('<option value='+value+'>'+value+'</option>')
                        // $('select[name=storage]').append('<option value='+value.storage_id+'>'+value.storage+'</option>')
                        // $('select[name=asin]').append('<option value='+value.asin+'>'+value.asin+'</option>')
                    });
                }
            })
        }

        function getStorageByColor(){
            $('select[name=asin]').children('option:not(:first)').remove()
            $('select[name=storage]').children('option:not(:first)').remove()
            $('select[name=network]').children('option:not(:first)').remove()
            $('select[name=network]').hide()
            $('input[name=network]').show()
            $('input[name=network]').val('')
            $('input[name=quantity]').val('')

            var lot_id = $('#m_select2_1').val()
            var color = $('select[name=color]').val()
            var model = $('select[name=model]').val()
            var brand = $('select[name=brand]').val()
            console.log('Lot_ID: ' + lot_id + ' Brand: ' + brand + ' Model: ' + model + ' Color: ' + color)
            $.ajax({
                type: "get",
                url: '{{route("get_storage_by_color")}}/'+lot_id+'?color='+color+'&model='+model+'&brand='+brand,
                success: function (data) {
                    if (data['network'] != null) {
                        $('input[name=network]').val(data['network'])
                    } else {
                        $("input[name=network]").hide()
                        $('#checkingnetwork').html('<select class="form-control" name="network"  required><option value="" selected>Select</option></select>')
                        $.each(data['networks'], function( index, value ) {
                            $('select[name=network]').append('<option value='+value+'>'+value+'</option>')
                        });
                    }

                    $.each(data['storage'], function( index, value ) {
                        $('select[name=storage]').append('<option value='+value.id+'>'+value.name+'</option>')
                    });
                    // $.each(data['networks'], function( index, value ) {
                    //     $('select[name=network]').append('<option value='+value.name+'>'+value.name+'</option>')
                    // });
                }
            })
        }


        function getAsinByStorage(){
            $('select[name=asin]').children('option:not(:first)').remove();
            $('input[name=quantity]').val('')

            var lot_id = $('#m_select2_1').val();
            var color = $('select[name=color]').val();
            var storage_id = $('select[name=storage]').val();
            var lot_brand = $('#brand_id').val()
            var model = $('select[name=model]').val();
            console.log(color + storage_id + lot_brand)

            $.ajax({
                type: "GET",
                url: '{{route("get_asin_by_storage_rest")}}/'+lot_id+'?color='+color+'&storage_id='+storage_id+'&lot_brand='+lot_brand+'&model='+model,
                success: function (data) {
                    $.each(data['asin'], function( index, value ) {
                        $('select[name=asin]').append('<option value='+value.asin+'>'+value.asin+'</option>')
                    });
                }
            })
        }

        function getAsinQuantityByAsin(){
            $('input[name=quantity]').val('')

            var lot_id = $('#m_select2_1').val()
            var color = $('select[name=color]').val()
            var storage_id = $('select[name=storage]').val()
            var asin = $('select[name=asin]').val()
            var brand = $('select[name=brand]').val()
            var model = $('select[name=model]').val()
            console.log(color + storage_id)

            $.ajax({
                type: "GET",
                url: '{{route("get_asin_by_storage_qty")}}/'+lot_id+'?color='+color+'&storage_id='+storage_id+'&asin='+asin+'&model='+model+'&brand='+brand,
                success: function (data) {
                    console.log(data+ 'working')
                    $.each(data['storage'], function( index, value ) {
                        console.log('Totla Q: '+value.asin_total_quantity + ' Inventort Q: ' + value.inventory_quantity)
                        $('input[name=quantity]').val(value.asin_total_quantity - value.inventory_quantity)
                        if($('input[name=quantity]').val() == 0){
                            $('.update_asin_qty_when_zero').removeClass('hide')
                        }
                    });
                }
            })
        }



        //delete wrongly added imei
        function delete_wrongly_added_imei(imei){
            $.ajax({
                type: "get",
                url: '{{route("inventory.delete.by_imei")}}?imei='+imei,
                success: function (data) {
                    console.log(data)
                    $('#'+imei).remove()
                    $('#btn'+imei).remove()
                    $('#bre'+imei).remove()
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
                        console.log(data + 'working')
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
                alert('Entered IMEI less than 14 numbers ')
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
                    $.ajax({
                        url: '{{route('inventory.store')}}',
                        dataType: 'json',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            lot_id: $('select[name=lot_id]').val(),
                            color: $('select[name=color]').val(),
                            storage_id: $('select[name=storage]').val(),
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
                                $('#imei_success').append('<br id=bre'+ imei + '><span id=' + imei + '>' + imei + '</span> ' +
                                    '<a id =btn' + imei + ' onclick="delete_wrongly_added_imei(' + imei + ')" ' +
                                    'style="cursor: pointer;color: red;">&nbsp;cancel</a>')
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
    </script>
@endpush
