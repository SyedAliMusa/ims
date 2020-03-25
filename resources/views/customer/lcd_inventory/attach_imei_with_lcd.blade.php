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
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Attach IMEI With LCD</h3>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row">
                        {{--release--}}
                        <div class="col-md-12" style="    background: ghostwhite;     border-right: 10px solid white;">
                            <h4 class="text-danger">{{session('deleted')}}</h4>
                            <div class="row" style="margin: 3%;">
                                <div class="col-md-12">
                                    <form method="POST" action="{{url()->current()}}" role="form" id="submit_form">
                                        {{csrf_field()}}
                                        @if (auth()->user()->account_type == 'refurbishing')
                                            <div class="row">
                                                <div class="col-lg-3 col-md-5 col-sm-6">
                                                    <div class="form-group">
                                                        <b class="text-primary">Scan IMEI</b>
                                                        <input type="text" class="form-control" name="imei" onchange="findIMEI()" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required autofocus="autofocus">
                                                        <small id="imei_exist"></small>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-5 col-sm-6">
                                                <div class="form-group">
                                                        <b class="text-primary">Scan LCD Barcode</b>
                                                        <input type="text" class="form-control" name="lcd_barcode" value="" onchange="findLCDbarcod()"  required>
                                                        <small id="lcd_barcode"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <b class="text-white">.</b>
                                                        <a href="javascript:{}" onclick="document.getElementById('submit_form').submit();" class="btn btn-primary">Submit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <h3>Only Refurbisher Can Bind IMEI To LCD</h3>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin: 3%;">
                        <div class="col-md-12">
                            <b>Attachement History</b>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>IMEI</th>
                                    <th>LCD</th>
                                    <th>Created_by</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (auth()->user()->account_type == 'admin')
                                    @foreach(\App\AttachIMEIToLCD::where('status','<>', 0)->orderByDesc('id')->get() as $item)
                                        <tr>
                                            <td>{{$item->inventory->imei}}</td>
                                            <td>{{$item->lcdInventory->barcode}}</td>
                                            <td>{{$item->user->name}}</td>
                                            <td>Marry                                        </td>
                                            <td>
                                                @if ($item->lcdInventory->status != 2)
                                                    <a class="btn btn-outline-warning btn-sm" href="{{url()->current()}}?attach_imei_to_lcd_id={{$item->id}}">Unmarry</a> </td>
                                            @else
                                                Dispatched
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach(\App\AttachIMEIToLCD::where('status','=', 1)->get() as $item)'
                                        @if($item->user->name == auth()->user()->name)
                                        <tr>
                                            <td>{{$item->inventory->imei}}</td>
                                            <td>{{$item->lcdInventory->barcode}}</td>
                                            <td>{{$item->user->name}}</td>
                                            <td>Marry</td>
                                            @if ($item->lcdInventory->status != 2)
                                            <td><a class="btn btn-outline-warning btn-sm" href="{{url()->current()}}?attach_imei_to_lcd_id={{$item->id}}">Unmarry</a></td>
                                            @else
                                                <td>Dispatched</td>
                                            @endif
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script>
        function findIMEI(){
            var imei =  $('[name=imei]').val();
            $.ajax({
                type: "get",
                url: '{{url()->current()}}?imei='+imei,
                success: function (data) {
                    console.log(data)
                    if (data){
                        $('[name=lcd_barcode]').focus();
                        $('#imei_exist').text('Succes')
                    }
                    else {
                        $('[name=imei]').val('');
                        $('#imei_exist').text('IMEI not found')
                    }
                }
            });
        }
        function findLCDbarcod(){
            var lcd_barcode =  $('[name=lcd_barcode]').val();
            $.ajax({
                type: "get",
                url: '{{url()->current()}}?lcd_barcode='+lcd_barcode,
                success: function (data) {
                    console.log(data)
                    if (data == 'ok'){
                        $('[name=lcd_barcode]').focus();
                        $('#lcd_barcode').text('Succes')
                    }
                    else {
                        $('[name=lcd_barcode]').val('');
                        $('#lcd_barcode').text('LCD not found ' +lcd_barcode)
                    }
                }
            });
        }
    </script>
@endpush
