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
                    <h3 class="m-subheader__title "> @if (auth()->user()->account_type == 'refurbishing') Add LCD Broken  @else LCD Release  @endif</h3>
                </div>
                <div>
                   <h3 class="m-subheader__title "> @if (auth()->user()->account_type == 'refurbishing') Add LCD Broken  @else LCD Receive  @endif</h3>
                </div>
            </div>
        </div>
        <div class="m-content">
            <!--<div class="m-portlet m-portlet--mobile">-->
                
                    
                            <div class="row">
                                <div class="col-md-6 m-portlet m-portlet--mobile border-right">
                                    <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-md-12 hide">
                                            <div class="form-group">
                                                <b class="text-primary">Status</b>
                                                <select class="form-control" name="statusRelease" required onchange="onReceiveStatusRelease()">
                                                    <option  value="" >select</option>
                                                    <option value="1" selected>Release</option>
                                                    <!--@foreach(config('general.lcd_status') as $key=>$status)-->
                                                        <!--<option value="{{$key}}" >{{$status}}</option>-->
                                                    <!--@endforeach-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 " id="showIssuedToRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Issue To</b>
                                                <select class="form-control" name="issued_toRelease" onchange="showAccountsRelease()">
                                                    <option  value="" selected>select</option>
                                                    @foreach(config('general.issued_to') as $key=>$issued_to)
                                                        <option value="{{$key}}">{{$issued_to}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showAccountsLCDRefRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Account Name </b>
                                                <select class="form-control" name="assigned_to_accountRelease" id="assigned_to_account_lcd_refRelease" ></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showAccountsRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Account Name </b>
                                                <select class="form-control" name="assigned_to_accountRelease" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\User::where('account_type', '=' ,'refurbishing')->get() as  $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showReasonRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Broken LCD Reason </b>
                                                <select class="form-control" name="reasonRelease">
                                                    <option value="" selected>select</option>
                                                    @foreach(config('general.lcd_reason') as $key=>$reason)
                                                        <option value="{{$key}}" >{{$reason}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showReceiverRelease">
                                            <div class="form-group">
                                                <b class="text-primary"> Name </b>
                                                <select class="form-control" name="receiver_nameRelease">
                                                    <option value="" selected>select</option>
                                                    <option value="Rainal" >Rainal</option>
                                                    <option value="Margrate" >Margrate</option>
                                                    <option value="Cristian" >Cristian</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12"  id="showBarcodeRelease">
                                            <div class="form-group on_error">
                                                <b class="text-primary">Scan Barcode</b>
                                                <input type="text" class="form-control input_border" onchange="CehckBarcodeRelease()" name="barcodeRelease" {{--oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%"--}} autofocus autocomplete="off">
                                                <small id="fail_releaseRelease" class="text-danger">{{session('fail_release')}}</small>
                                                <small id="success_releaseRelease" class="text-success">{{session('success_release')}}</small>
                                                <small id="already_verifiedRelease" class="text-primary">{{session('already_verified')}}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showColorRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Color </b>
                                                <select class="form-control" name="colorRelease" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\Lot::groupBy('color')->get() as  $color)
                                                        <option value="{{$color->color}}">{{$color->color}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showCategoryRelease">
                                            <div class="form-group">
                                                <b class="text-primary">Category </b>
                                                <select class="form-control" name="category_idRelease" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\Category::all() as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showSaveButtonRelease">
                                            <div class="form-group">
                                                <b class="text-white"></b>
                                                <button type="button" onclick="CehckBarcodeRelease()" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6 m-portlet m-portlet--mobile border-left">
                                    <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-md-12 hide">
                                            <div class="form-group">
                                                <b class="text-primary">Status</b>
                                                <select class="form-control" name="status" required onchange="onReceiveStatus()">
                                                    <option  value="" >select</option>
                                                    <option value="2" selected>Receive</option>
                                                    <!--<option value="1">Release</option>-->
                                                    <!--@foreach(config('general.lcd_status') as $key=>$status)-->
                                                        <!--<option value="{{$key}}" >{{$status}}</option>-->
                                                    <!--@endforeach-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showIssuedTo">
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
                                        <div class="col-md-12 hide" id="showAccountsLCDRef">
                                            <div class="form-group">
                                                <b class="text-primary">Account Name </b>
                                                <select class="form-control" name="assigned_to_account" id="assigned_to_account_lcd_ref" ></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showAccounts">
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
                                        <div class="col-md-12 hide" id="showReason">
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
                                        <div class="col-md-12 hide" id="showReceiver">
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
                                        <div class="col-md-12"  id="showBarcode">
                                            <div class="form-group on_error">
                                                <b class="text-primary">Scan Barcode</b>
                                                <input type="text" class="form-control input_border" onchange="CehckBarcode()" name="barcode" {{--oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%"--}} autofocus autocomplete="off">
                                                <small id="fail_release" class="text-danger">{{session('fail_release')}}</small>
                                                <small id="success_release" class="text-success">{{session('success_release')}}</small>
                                                <small id="already_verified" class="text-primary">{{session('already_verified')}}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showColor">
                                            <div class="form-group">
                                                <b class="text-primary">Color </b>
                                                <select class="form-control" id="cool" name="color" >
                                                    <option  value="" >select</option>
                                                    @foreach(\App\Lot::groupBy('color')->get() as  $color)
                                                        <option value="{{$color->color}}">{{$color->color}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showCategory">
                                            <div class="form-group">
                                                <b class="text-primary">Category </b>
                                                <select class="form-control" id="catg" name="category_id" >
                                                    <option  value="" selected>select</option>
                                                    @foreach(\App\Category::all() as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 hide" id="showSaveButton">
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

 
@stop
@push('scripts')
    <script>
        function showAccountsRelease() {
            var issued_to = $('select[name=issued_toRelease] :selected').val();
            if (issued_to == 'Phone_Refurbished') {
                $('#showAccountsRelease').removeClass('hide');
                $('#showAccountsLCDRefRelease').addClass('hide');
            }else {
                $('#showAccountsRelease').addClass('hide');
                $('#showAccountsLCDRefRelease').removeClass('hide');
                $('#assigned_to_account_lcd_refRelease').html('<option value="">select account</option>');
                $('#assigned_to_account_lcd_refRelease').append('<option value="49">Fatima</option>\n' +
                    '<option value="50">Rosy</option>\n' +
                    '<option value="51">Sindy</option>\n' +
                    '<option value="52">Farnanda</option>');
            }
            $('#fail_releaseRelease').html('');
            $('#success_releaseRelease').html('');
            $('#already_verifiedRelease').html('');
        }

        function onReceiveStatusRelease() {
            $('#fail_releaseRelease').html('');
            $('#success_releaseRelease').html('');
            $('#already_verifiedRelease').html('');
            var status = $('select[name=statusRelease] :selected').val();
            if (status == '1') { // release
                // $('#showIssuedToRelease').removeClass('hide');
                $('#showAccountsRelease').addClass('hide');
                $('#showAccountsLCDRefRelease').addClass('hide');
                $('#showReasonRelease').addClass('hide');
                $('#showCategoryRelease').addClass('hide');
                $('#showColorRelease').addClass('hide');
            }else {
                $('#issued_toRelease').addClass('hide');
                $('#showAccountsRelease').addClass('hide');
                $('#showAccountsLCDRefRelease').addClass('hide');
                $('#reasonRelease').addClass('hide');
                $('#on_received_show_cat').addClass('hide');
            }
        }

        function CehckBarcodeRelease() {
            var status = $('select[name=statusRelease] :selected').val();
            var barcode = $('input[name=barcodeRelease]').val();
            var category_id = $('select[name=category_idRelease] :selected').val();
            var color = $('select[name=colorRelease] :selected').val();
            var issued_to = $('select[name=issued_toRelease] :selected').val();
            var assigned_to_account = $('select[name=assigned_to_accountRelease] :selected').val();
            var reason = $('select[name=reasonRelease] :selected').val();
            var receiver_name = $('select[name=receiver_nameRelease] :selected').val();
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
                            $('#showCategoryRelease').removeClass('hide');
                            $('#showColorRelease').removeClass('hide');
                            $('#showSaveButtonRelease').removeClass('hide');
                        }else if(data['assigned_to']) {
                            $('#showAccountsRelease').removeClass('hide');
                            $('select[name=assigned_to_accountRelease]').append('<option value="'+data['assigned_to']+'" selected>'+data['assigned_to_name']+'</option>');
                            $('#showReasonRelease').removeClass('hide');
                            $('#showCategoryRelease').addClass('hide');
                            $('#showColorRelease').addClass('hide');
                            $('#showSaveButtonRelease').removeClass('hide');
                            $('#fail_releaseRelease').html('');
                            $('#success_releaseRelease').html(data);
                            $('#already_verifiedRelease').html('');
                        }
                        else if(data == 'Rainel') {
                            $('#showAccountsRelease').addClass('hide');
                            $('#showReasonRelease').removeClass('hide');
                            $('#showReceiverRelease').removeClass('hide');
                            $('#showCategoryRelease').addClass('hide');
                            $('#showColorRelease').addClass('hide');
                            $('#showSaveButtonRelease').removeClass('hide');
                            $('#fail_releaseRelease').html('');
                            $('#success_releaseReleaseRelease').html(data);
                            $('#already_verifiedRelease').html('');
                        }
                        else if(data == 'LCD Received' || data == 'LCD Released'|| data == 'LCD Added Into Broken list') {
                            $('select[name=category_idRelease]').val($("select[name=category_idRelease] option:first").val());
                            $('select[name=colorRelease]').val($("select[name=color] option:first").val());
                            $('#showCategoryRelease').addClass('hide');
                            $('#showColorRelease').addClass('hide');
                            $('#showSaveButtonRelease').addClass('hide');
                            $('input[name=barcodeRelease]').val('')
                            $('#fail_releaseRelease').html('');
                            $('#success_releaseRelease').html(data);
                            $('#already_verifiedRelease').html('');
                        }
                        else if(data == 'LCD not released' || data == 'Ooops! LCD not found!'|| data == 'LCD Already Released' ) {
                            $('input[name=barcodeRelease]').val('')
                            $('#fail_releaseRelease').html(data);
                            $('#success_releaseRelease').html('');
                            $('#already_verifiedRelease').html('');
                        }
                    }
                });
            }
        }
    </script>
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
            if (status == '2') { // receive
                $('#showIssuedTo').addClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#showReason').addClass('hide');
                $('#showCategory').addClass('hide');
                $('#showColor').addClass('hide');
            }
            else {
                $('#issued_to').addClass('hide');
                $('#showAccounts').addClass('hide');
                $('#showAccountsLCDRef').addClass('hide');
                $('#reason').addClass('hide');
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
                            if(color){
                               
                            }else{
                                $('#showColor').addClass('hide');
                                 $('#showCategory').addClass('hide');
                                 $('#showSaveButton').removeClass('hide');
                            }
                            $('#showReason').removeClass('hide');
                            //$('#showCategory').addClass('hide');
                          
                          //  $('#showSaveButton').removeClass('hide');
                            $('#fail_release').html('');
                            $('#success_release').html(data);
                            $('#already_verified').html('');
                            
                        }
                        else if(data == 'Rainel') {
                            if(color){
                              
                            }else{
                                $('#showColor').addClass('hide');
                                 $('#showCategory').addClass('hide');
                                 $('#showSaveButton').removeClass('hide');
                            }
                            $('#showAccounts').addClass('hide');
                            $('#showReason').removeClass('hide');
                            $('#showReceiver').removeClass('hide');
                           // $('#showCategory').addClass('hide');
                           // $('#showColor').addClass('hide');
                            //$('#showSaveButton').removeClass('hide');
                            $('#fail_release').html('');
                            $('#success_release').html(data);
                            $('#already_verified').html('');
                        }
                        else if(data == 'LCD Received' || data == 'LCD Released'|| data == 'LCD Added Into Broken list') {
                            if(color){
                                //document.getElementById('cool').value = color;
                               
                            }else{
                                $('#showColor').addClass('hide');
                                $('#showCategory').addClass('hide');
                                $('#showSaveButton').removeClass('hide');
                            }
                            $('select[name=category_id]').val($("select[name=category_id] option:first").val());
                            $('select[name=color]').val($("select[name=color] option:first").val());
                            //$('#showCategory').addClass('hide');
                            //$('#showColor').addClass('hide');
                           // $('#showSaveButton').addClass('hide');
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
                         if(color){
                            document.getElementById('cool').value = color;
                            document.getElementById('catg').value = category_id;
                         }
                    }
                });
            }
        }
    </script>
@endpush