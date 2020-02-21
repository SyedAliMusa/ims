@extends('layouts.customer.app')

@section("title")
    Lot | Create
@endsection
@push("css")
    {{--include internal css--}}
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Lot > Create</h3>
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
                    <form action="{{route('lots.store')}}" method="post" class="form-horizontal" id="lot_insert_form" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Lot_ID</label>
                                    <input type="text" class="form-control" name="lot_id" id=""  value="" oninput="this.value=this.value.replace(/[^0-9a-zA-Z-_]/g,'');" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <select class="form-control" name="brand" id="sel1" required>
                                        <option value="" selected></option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" name="model" id="" value="" oninput="this.value=this.value.replace(/[^0-9a-zA-Z-_]/g,'');" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <select class="form-control" name="network" id="sel1" required>
                                        <option value="" selected></option>
                                        @foreach($networks as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="usr">Bought Qty</label>
                                        <input type="number" class="form-control" name="bought_qty" id=""  value="" required>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group ">
                                        <label for="usr">Received Qty</label>
                                        <input type="number" class="form-control" name="received_qty" id=""  value="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4  offset-1">
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" name="color" id=""  value="" oninput="this.value=this.value.replace(/[^a-zA-Z-_]/g,'');" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <select class="form-control" name="storage" id="select_storage" required>
                                        <option value="" selected></option>
                                        @foreach($storages as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" id=""  value="" required>
                                    <input type="hidden" name="added" value="0">
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">ASIN#</label>
                                    <div id="append">
                                        <input type="text" class="form-control" name="asin" id=""  value="" required>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-warning" style="width: 100%"> Save + Add_more</button>
                            </div>
                            <div class="col-md-1" style="padding-top: 1%;">
                                <span id="imei_success" style="color: blue"></span>
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
    <script>
        $(document).ready(function() {
            $("input[name=color]").on('change, keyup', function() {
                var brand =  $('select[name=brand] :selected').text();
                var network =  $('select[name=network] :selected').text();
                var model =  $('input[name=model]').val();
                var color =  $('input[name=color]').val();
                var storage =  $('select[name=storage]').val();

                console.log(brand +' '+network +' '+model +' '+color +' '+storage )
                $.ajax({
                    type: "GET",
                    url: '{{route("get_asin_by__")}}?brand_id='+brand+'&model='+model+'&color='+color+'&network_id='+network+'&storage_id='+storage,
                    success: function (data) {
                        if (data.length >0) {
                            $('#append').html(' <select class="form-control" name="asin" required></select>')
                            $.each(data, function (index, value) {
                                $('select[name=asin]').append('<option value=' + value.asin + '>' + value.asin + '</option>')
                            });
                        }
                        else {
                            $('#append').html('<input type="text" class="form-control" name="asin" value="" required>')
                        }
                    }
                });
            })

            $("#select_storage").on('change, click', function() {

                var brand =  $('select[name=brand] :selected').text();
                var network =  $('select[name=network] :selected').text();
                var model =  $('input[name=model]').val();
                var color =  $('input[name=color]').val();
                var storage =  $('select[name=storage]').val();

                console.log(brand +' '+network +' '+model +' '+color +' '+storage )


                $.ajax({
                    type: "GET",
                    url: '{{route("get_asin_by__")}}?brand_id='+brand+'&model='+model+'&color='+color+'&network_id='+network+'&storage_id='+storage,
                    success: function (data) {


                        if (data.length >0) {
                            $('#append').html(' <select class="form-control" name="asin" required></select>')
                            $.each(data, function (index, value) {
                                $('select[name=asin]').append('<option value=' + value.asin + '>' + value.asin + '</option>')
                            });
                        }
                        else {
                            $('#append').html('<input type="text" class="form-control" name="asin" value="" required>')
                        }
                    }
                });
            })

        } );

        $('form#lot_insert_form').on('submit', function(e) {
            e.preventDefault();
            var y = $('input[name=added]').val()
            var z = $('input[name=quantity]').val()
            var x = +y + +z;
            var received_qty = $('input[name=received_qty]').val();
            if(received_qty < x){
                alert('Entered quantity exceeds from received quantity! you cannot add any more or recheck you entered')
            }
            else {
                var is_asin_input_exist = $('input[name=asin]').val()
                var is_select_asin_input_exist = $('select[name=asin] :selected').val()
                if (is_asin_input_exist) {
                    var asin = $('input[name=asin]').val()
                }
                else {
                    var asin = $('select[name=asin]').val()
                }

                // alert(asin)
                $.ajax({
                    url: '{{route('lots.store')}}',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        _token: '{{csrf_token()}}',
                        lot_id: $('input[name=lot_id]').val(),
                        brand: $('select[name=brand]').val(),
                        network: $('select[name=network]').val(),
                        model: $('input[name=model]').val(),
                        color: $('input[name=color]').val(),
                        storage: $('select[name=storage]').val(),
                        quantity: $('input[name=quantity]').val(),
                        received_qty: $('input[name=received_qty]').val(),
                        bought_qty: $('input[name=bought_qty]').val(),
                        asin: asin,
                    },
                    success: function (data, textStatus, jQxhr) {
                        console.log(data)

                        $('#imei_success').append('<br>' + data.asin + '')
                        $('input[name=quantity]').val('')
                        $('input[name=asin]').val('')
                        $('select[name=asin]').val('')
                        $('input[name=added]').val(data.asin_total_quantity)
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                })
            }
        })

        $(document).ready(function() {

        } );

    </script>
@endpush