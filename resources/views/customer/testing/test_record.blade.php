@extends('layouts.customer.app')

@section("title")
    TestRecordByIMEI
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title "><p style="color: green;">Scan IMEI Number To Check WhoEver Have Tested it.</p></h3>
                </div>
                {{--<div>
                    <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                        <span class="m-subheader__daterange-label">
										<span class="m-subheader__daterange-title"></span>
										<span class="m-subheader__daterange-date m--font-brand"></span>
                        </span>
                        <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
										<i class="la la-angle-down"></i>
                        </a>
                    </span>
                </div>--}}
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">

                <div class="row">
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=black">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: black">
                                    <strong>Black</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=purple">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: purple">
                                    <strong>Purple</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=blue">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: blue">
                                    <strong>Blue</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=green">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: green">
                                    <strong>Green</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=pink">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: deeppink">
                                    <strong>Pink</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=red">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: red">
                                    <strong>Red</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <a href="/reports/colorbased?color=orange">
                            <div style="position: relative; text-align: center; color: white;">
                                <img src="https://img.icons8.com/plasticine/100/000000/folder-invoices.png"/>
                                <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); color: orangered">
                                    <strong>Orange</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <div class="col-md-12">
                                <form method="get" action="{{url()->current()}}" class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" style="color: green;" class="form-control" name="imei" title="imei" placeholder="Enter IMEI Number" value="" autocomplete="on">
                                                <button type="submit" style="color: green"
                                                        class="btn btn-outline-success">Get Test Record
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{--<div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a class="btn btn-brand" href="{{route('ExportRedFlag')}}?from={{request()->input('from')}}&to={{request()->input('to')}}&issued_to_for_report={{request()->input('issued_to_for_report')}}">Export Excel</a>
                            </li>
                        </ul>
                    </div>--}}
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <p class="text-info"></p>
                    <table class="table table-striped table-bordered table-hover" style="color: green;">
                        <thead>
                        <tr>
                            <th>sr</th>
                            <th>Tester Name</th>
                            <th>Tested Date</th>
                            <th>Category Saved</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1 ?>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$product->user_name}}</td>
                                <td>{{date('d-m-Y', strtotime($product->created_at))}}</td>
                                <td>{{$product->cat_new_name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    {{--datetimepicker wirh moment js--}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
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
    </script>
@endpush
