@extends('layouts.customer.app')

@section("title")
    Attach IMEI With LCD
@endsection
@push("css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css">
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
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
                    <h3 class="m-subheader__title ">LCD Inventory Report</h3>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row" style="margin: 3%;">
                        <div class="col-md-12">
                            <form action="{{url()->current()}}">
                                <div class="row" style="margin: 3%;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b class="text-primary">From</b>
                                            <input type="text"  class="form-control" id="datepicker_from" name="from" placeholder="From" value="{{request()->input('from')}}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b class="text-primary">To</b>
                                            <input type="text"  class="form-control" id="datepicker_to" name="to"  placeholder="To" value="{{request()->input('to')}}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b class="text-primary">Status</b>
                                            <select class="form-control" name="status">
                                                <option class="hide" value="" selected>select</option>
                                                @foreach(config('general.lcd_status') as $key=>$status)
                                                    @if ($key == request()->input('status'))
                                                        <option value="{{$key}}" selected >{{$status}}</option>
                                                    @else
                                                        <option value="{{$key}}" >{{$status}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b class="text-primary">Refurbisher</b>
                                            <select class="form-control" name="refurbisher" onchange="getLCDInventoryAccount()">
                                                <option class="hide" value="" selected>select</option>
                                                @foreach(config('general.issued_to') as $key=>$status)
                                                    @if ($key == request()->input('status'))
                                                        <option value="{{$key}}" selected >{{$status}}</option>
                                                    @else
                                                        <option value="{{$key}}" >{{$status}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b class="text-primary">Account Name</b>
                                            <select class="form-control" name="account"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <b class="text-white">.</b>
                                            <button type="submit" class="form-control btn btn-warning" style="width: 100% !important;">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>modal</th>
                                    <th>Category</th>
                                    <th>Color</th>
                                    <th>LCD</th>
                                    <th>Name</th>
                                    <th>Created_by</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->brand->name}}</td>
                                        <td>{{$item->modal}}</td>
                                        <td>{{$item->category->name}}</td>
                                        <td>{{$item->color}}</td>
                                        <td>{{$item->barcode}}</td>
                                        @if ($item->issued->user)
                                            <td>{{$item->issued->user->name}}</td>
                                        @else
                                            <td>{{$item->issued->receiver_name}}</td>
                                        @endif
                                        <td>{{$item->user->name}}</td>
                                    </tr>
                                @endforeach
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    {{--datetimepicker wirh moment js--}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <script>
        $(document).ready(function () {
            $('#datepicker_from').datepicker({
                uiLibrary: 'bootstrap'
            });
            $('#datepicker_to').datepicker({
                uiLibrary: 'bootstrap'
            });
        });
        function getLCDInventoryAccount() {
            var refurbisher =  $('select[name=refurbisher] :selected').val();
            console.log(refurbisher)
            $.ajax({
                type: "GET",
                url: "{{url()->current()}}?ajax=true&refurbisher="+refurbisher,
                success: function (data) {
                    console.log(data)
                    $('select[name=account]').html('<option value="" selected> Account Name </option>')
                    if (refurbisher == 'LCD_Refurbished'){
                        $.each(data, function (index, value) {
                            $('select[name=account]').append('<option value=' + value.receiver_name + '>' + value.receiver_name + '</option>')
                        });
                    }else {
                        $.each(data, function (index, value) {
                            $('select[name=account]').append('<option value=' + value.user.id + '>' + value.user.name + '</option>')
                        });
                    }
                }
            });
        }
    </script>
@endpush