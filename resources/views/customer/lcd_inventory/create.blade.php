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
                    <h3 class="m-subheader__title ">LCD Inventory | Create</h3>
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
                    <form class="form-horizontal" id="lcdinventory_insert_form" role="form">
                        <div class="row">
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0 on_error">
                                    <label for="usr">Barcode</label>
                                    <input type="text" class="form-control" name="barcode" id="" onchange="findBarcode()" value="" autofocus  required>
                                    <small id="barcode_exist" class="text-danger"></small>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" id="brand_name" disabled>
                                    <input type="hidden" class="form-control" name="brand">
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" name="model"  disabled>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Category</label>
                                    <input type="text" class="form-control" id="category_name" disabled>
                                    <input type="hidden" class="form-control" name="category">
                                </div>
                                <div class="form-group margin-0">
                                    <label class="text-white">.</label>
                                    <input type="hidden" name="submit" class="btn btn-warning">
                                </div>
                            </div>
                            <div class="col-md-2 offset-1" style="padding-top: 1%;">
                                <span  style="color: blue" id="barcode_success"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
@stop
@push('scripts')

    <script>
        function findBarcode(){
            var barcode = $('input[name=barcode]').val();
            $.ajax({
                url: '{{route('lcd_inventory.check_barcode_is_exist')}}?barcode='+barcode,
                dataType: 'json',
                type: 'get',
                success: function (data, textStatus, jQxhr) {
                    if (data == 0) {
                        $('.on_error').addClass('has-error')
                        $('#barcode_exist').html("LCD already exist! you can't put it again")
                        barcode = $('input[name=barcode]').val('')

                    }else {
                        $('input[name=model]').val(data['modal'])
                        $('input[name=brand]').val(data['brand_id'])
                        $('input[id=brand_name]').val(data['brand_name'])
                        $('input[id=category_name]').val(data['category'])
                        $('input[name=category]').val(data['category_id'])


                        $('#barcode_exist').html('')
                        $.ajax({
                            url: '{{route('lcd_inventory.store')}}',
                            dataType: 'json',
                            type: 'post',
                            data: {
                                _token: '{{csrf_token()}}',
                                barcode: $('input[name=barcode]').val(),
                                brand_id: $('input[name=brand]').val(),
                                model: $('input[name=model]').val(),
                                category_id: $('input[name=category]').val(),
                            },
                            success: function (data, textStatus, jQxhr) {
                                if (data == 0) {
                                    $('.on_error').addClass('has-error')
                                    $('#imei_exist').html("Barcode already exist! you can't put it again")
                                }
                                else {
                                    $('.on_error').removeClass('has-error')
                                    var barcode = $('input[name=barcode]').val()
                                    $('#barcode_success').append('<br><p id=' + barcode + '>' + barcode + '</p></a>')
                                    $('input[name=barcode]').val('')
                                }
                            },
                            error: function (jqXhr, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        })
                    }

                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            })
        }
    </script>
@endpush
