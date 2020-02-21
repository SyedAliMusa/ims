@extends('layouts.customer.app')

@section("title")
    Dispatch Report
@endsection
@push("css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css">
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">    Dispatch Report                    </h3>
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
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">All

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-2">
                            <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                <button type="submit" class="btn btn-warning">Reset all</button>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                <div class="form-group">
                                    <input type="text" required class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                                    <input type="text" required class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">

                                    <select class="form-control" name="tracking_id" >
                                        <option value=""> vendor</option>
                                        @foreach($vendors as $brand)
                                            <option value="{{$brand->tracking}}">{{$brand->tracking}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="{{route('report.dispatch.export')}}" method="get" class="form-inline" role="form">
                                <input type="hidden" name="from" value="{{request()->input('from')}}">
                                <input type="hidden" name="to" value="{{request()->input('to')}}">
                                <input type="hidden" name="tracking_id" value="{{request()->input('tracking_id')}}">
                                <button type="submit" class="btn btn-warning">Export CSV</button>
                            </form>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-10 offset-1">
                            @if(count($products) > 0)
                                <?php
                                $date = array();
                                $total_imei = array();
                                ?>
                                @foreach($products as $product)
                                    <?php
                                    array_push($date ,$product->date);
                                    array_push($total_imei,$product->sold);
                                    ?>
                                @endforeach
                            @else
                                <?php $total_imei = [];
                                $date = []?>
                            @endif
                            <canvas id="myChart" width="900" height="400" style="    width: 1000px !important;
                                    height: 400px !important;"></canvas>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-10 offset-1">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Sold</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ date('M-d-Y', strtotime($product->date))}}</td>
                                        <td class="text-danger">{{$product->sold}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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

        var date= JSON.parse('<?php echo json_encode($date); ?>')
        var total_imei= JSON.parse('<?php echo json_encode($total_imei); ?>')

        var canvas = document.getElementById('myChart');

        var data = {

            labels: date,
//                        labels: ["2008", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            datasets: [
                {
                    label: "testing report",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 5,
                    pointHitRadius: 10,
                    data: total_imei
                }
            ]
        };

        function adddata(){
            myLineChart.data.datasets[0].data[7] = 60;
            myLineChart.data.labels[7] = "Newly Added";
            myLineChart.update();
        }

        var option = {
            showLines: true
        };
        var myLineChart = Chart.Line(canvas, {
            data: data,
            options: option,
        })
    </script>
@endpush



