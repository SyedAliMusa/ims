@extends('layouts.customer.app')

@section("title")
    Dispatch | Create
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
                    <h3 class="m-subheader__title ">Dispatch | Create</h3>
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
                    <h4 class="text-danger">{{session('message')}}</h4>

                    <form action="{{route('dispatch.store')}}" method="post" class="form-horizontal" id="form_dispatch"
                          role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" disabled  id="brand" value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" disabled id="model"value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <input type="text" class="form-control" disabled id="network" id=""  value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <input type="text" class="form-control" disabled id="storage" id=""  value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" disabled  id="color" value="" required>
                                </div>
                            </div>
                            <div class="col-md-4 offset-1">

                                <div class="form-group margin-0 on_error">
                                    <label for="usr">IMEI</label>
                                    <input type="text" class="form-control" name="imei" id="imei_id_val" onchange="getLotByimei()" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required autofocus="autofocus">
                                    <small id="imei_exist" class="text-danger"></small>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Category</label>
                                    <input type="text" class="form-control" disabled  id="category" value="" required >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Tracking_ID</label>
                                    <input type="text" class="form-control" tabindex="0" name="tracking_id" maxlength="30" value="" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">save dispatch or add more IMEI</label>
                                    <button type="submit" class="btn btn-warning" style="width: 100%">Save
                                        Dispatch</button>
                                </div>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <span class="text-danger" id="imei_success"></span>
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
        function getLotByimei() {
            var imei_no = $('#imei_id_val').val();
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_imei_for_dispatch")}}/' + imei_no,
                success: function (data) {
                    console.log(data)
                    if(data['already_dispatched']){
                        $('.on_error').addClass('has-error')
                        $('#imei_exist').html("Imei already dispatched! you can't dispatch it again")
                    }
                    else if(data['not_tested']){
                        $('.on_error').addClass('has-error')
                        $('#imei_exist').html("Imei not tested!")
                    }
                    else if(data['not_found']){
                        $('.on_error').addClass('has-error')
                        $('#imei_exist').html("Imei not found!")
                    }
                    else {
                        $('.on_error').removeClass('has-error')
                        $('#imei_exist').html("")
                        $('input[id=brand]').val(data['brand'])
                        $('input[id=model]').val(data['model'])
                        $('input[id=network]').val(data['network'])
                        $('input[id=storage]').val(data['storage'])
                        $('input[id=color]').val(data['color'])
                        $('input[id=category]').val(data['category'])

                    }
                }
            });
        }

    </script>
@endpush