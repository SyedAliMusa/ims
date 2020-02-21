@extends('layouts.customer.app')

@section("title")
    Stock Out
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .hide{
            display: none !important;
        }
        .bottom-div{
            margin-top:-100px;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title "> @if (auth()->user()->account_type == 'refurbishing') Add LCD Broken  @else LCD Broken  @endif</h3>
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
                        {{--release--}}
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2 hide ">
                                            <div class="form-group">
                                                <b class="text-primary">Status</b>
                                                <select class="form-control" name="status" required onchange="onReceiveStatus()">
                                                    <option  value="" >select</option>
                                                    <option value="3" selected >Broken</option>
                                                    <!--@foreach(config('general.lcd_status') as $key=>$status)-->
                                                    <!--    <option value="{{$key}}" >{{$status}}</option>-->
                                                    <!--@endforeach-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showIssuedTo">
                                            <div class="form-group">
                                                <b class="text-primary">Issue To</b>
                                                <select class="form-control" name="issued_to" onchange="showAccounts()">
                                                    <option  value="" selected>select</option>
                                                    @foreach(config('general.issued_to') as $key=>$issued_to)
                                                        <option value="{{$key}}">{{$issued_to}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showAccountsLCDRef">
                                            <div class="form-group">
                                                <b class="text-primary">Account Name </b>
                                                <select class="form-control" name="assigned_to_account" id="assigned_to_account_lcd_ref" ></select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showAccounts">
                                            <div class="form-group">
                                                <b class="text-primary">Account Name </b>
                                                <select class="form-control" name="assigned_to_account" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\User::where('account_type','=', 'refurbishing')->get() as  $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 " id="showReason">
                                            <div class="form-group">
                                                <b class="text-primary">Broken LCD Reason </b>
                                                <select class="form-control" name="reason">
                                                    <option value="" selected>select</option>
                                                    @foreach(config('general.lcd_reason') as $key=>$reason)
                                                        <option value="{{$key}}" >{{$reason}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showReceiver">
                                            <div class="form-group">
                                                <b class="text-primary"> Name </b>
                                                <select class="form-control" name="receiver_name">
                                                    <option value="" selected>select</option>
                                                    <option value="Rainal" >Rainal</option>
                                                    <option value="Margrate" >Margrate</option>
                                                    <option value="Cristian" >Cristian</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3"  id="showBarcode">
                                            <div class="form-group on_error">
                                                <b class="text-primary">Scan Barcode</b>
                                                <input type="text" class="form-control input_border" onchange="CehckBarcode()" name="barcode" {{--oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%"--}} autofocus autocomplete="off">
                                                <small id="fail_release" class="text-danger">{{session('fail_release')}}</small>
                                                <small id="success_release" class="text-success">{{session('success_release')}}</small>
                                                <small id="already_verified" class="text-primary">{{session('already_verified')}}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showColor">
                                            <div class="form-group">
                                                <b class="text-primary">Color </b>
                                                <select class="form-control" name="color" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\Lot::groupBy('color')->get() as  $color)
                                                        <option value="{{$color->color}}">{{$color->color}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 hide" id="showCategory">
                                            <div class="form-group">
                                                <b class="text-primary">Category </b>
                                                <select class="form-control" name="category_id" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\Category::all() as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 hide" id="showSaveButton">
                                            <div class="form-group">
                                                <b class="text-white"></b>
                                                <button type="button" onclick="CehckBarcode()" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@push('scripts')
    <script>
        function showAccounts() {
            var issued_to = $('select[name=issued_to] :selected').val();
            if (issued_to == 'Phone_Refurbished') {
                $('#showAccounts').removeClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
            }else {
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').removeClass('hide');
                $('#assigned_to_account_lcd_ref').html('<option value="">select account</option>');
                $('#assigned_to_account_lcd_ref').append('<option value="49">Fatima</option>\n' +
                    '<option value="50">Rosy</option>\n' +
                    '<option value="51">Sindy</option>\n' +
                    '<option value="52">Farnanda</option>');
            }
            $('#fail_release').html('');
            $('#success_release').html('');
            $('#already_verified').html('');
        }

        function onReceiveStatus() {
            $('#fail_release').html('');
            $('#success_release').html('');
            $('#already_verified').html('');
            var status = $('select[name=status] :selected').val();
            if (status == '1') { // release
                $('#showIssuedTo').removeClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#showReason').addClass('hide');
                $('#showCategory').addClass('hide');
                $('#showColor').addClass('hide');
            }
            else if (status == '2') { // receive
                $('#showIssuedTo').addClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#showReason').addClass('hide');
                $('#showCategory').addClass('hide');
                $('#showColor').addClass('hide');
            }
            else if (status == '3') { // broken
                $('#showReason').removeClass('hide');
                $('#issued_to').addClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#on_received_show_cat').addClass('hide');
            }else {
                $('#issued_to').addClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#showReason').addClass('hide');
                $('#on_received_show_cat').addClass('hide');
            }
        }

        function CehckBarcode() {
            var status = $('select[name=status] :selected').val();
            var barcode = $('input[name=barcode]').val();
            var category_id = $('select[name=category_id] :selected').val();
            var color = $('select[name=color] :selected').val();
            var issued_to = $('select[name=issued_to] :selected').val();
            var assigned_to_account = $('select[name=assigned_to_account] :selected').val();
            var reason = $('select[name=reason] :selected').val();
            var receiver_name = $('select[name=receiver_name] :selected').val();
            if (barcode) {
                $.ajax({
                    type: "get",
                    url: '{{route("lcd_inventory.issue_lcd")}}?status='+status
                        +'&barcode='+barcode+'&category_id='
                        +category_id+'&color='+color
                        +'&issued_to='+issued_to+'&assigned_to_account='+assigned_to_account
                        +'&reason='+reason+'&receiver_name='+receiver_name,
                    success: function (data) {
                        console.log(data)
                        if (data == 'show_cat_and_color') {
                            $('#showCategory').removeClass('hide');
                            $('#showColor').removeClass('hide');
                            $('#showSaveButton').removeClass('hide');
                        }else if(data['assigned_to']) {
                            $('#showAccounts').removeClass('hide');
                            $('select[name=assigned_to_account]').append('<option value="'+data['assigned_to']+'" selected>'+data['assigned_to_name']+'</option>');
                            $('#showReason').removeClass('hide');
                            $('#showCategory').addClass('hide');
                            $('#showColor').addClass('hide');
                            $('#showSaveButton').removeClass('hide');
                            $('#fail_release').html('');
                            $('#success_release').html(data);
                            $('#already_verified').html('');
                        }
                        else if(data == 'Rainel') {
                            $('#showAccounts').addClass('hide');
                            $('#showReason').removeClass('hide');
                            $('#showReceiver').removeClass('hide');
                            $('#showCategory').addClass('hide');
                            $('#showColor').addClass('hide');
                            $('#showSaveButton').removeClass('hide');
                            $('#fail_release').html('');
                            $('#success_release').html(data);
                            $('#already_verified').html('');
                        }
                        else if(data == 'LCD Received' || data == 'LCD Released'|| data == 'LCD Added Into Broken list') {
                            $('select[name=category_id]').val($("select[name=category_id] option:first").val());
                            $('select[name=color]').val($("select[name=color] option:first").val());
                            $('#showCategory').addClass('hide');
                            $('#showColor').addClass('hide');
                            $('#showSaveButton').addClass('hide');
                            $('input[name=barcode]').val('')
                            $('#fail_release').html('');
                            $('#success_release').html(data);
                            $('#already_verified').html('');
                        }
                        else if(data == 'LCD not released' || data == 'Ooops! LCD not found!'|| data == 'LCD Already Released' ) {
                            $('input[name=barcode]').val('')
                            $('#fail_release').html(data);
                            $('#success_release').html('');
                            $('#already_verified').html('');
                        }
                    }
                });
            }
        }
    </script>
@endpush