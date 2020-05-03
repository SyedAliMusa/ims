@extends('layouts.customer.app')

@section("title")
    Color's Performance Report
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
                    <h3 class="m-subheader__title ">    color's Performance Report                    </h3>
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
                            <h3 class="m-portlet__head-text">
                                <form action="{{route('getcolors')}}" method="get" class="form-inline" role="form">
                                    <div class="form-group">
                                        Find Color(s) By IMIE &nbsp; <input type="text" class="form-control" name="imei" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required autofocus="autofocus">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="width: 100px !important"> filter </button>
                                    </div>
                                </form>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-10">
                            <form action="{{route('getcolors')}}" method="get" class="form-inline" role="form">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                                    <input type="text" class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">
                                    <select class="form-control" name="colors" id="colors" >
                                        <option value="">Select Color Folder</option>
                                        <option value="black">Black</option>
                                        <option value="purple">Purple</option>
                                        <option value="blue">Blue</option>
                                        <option value="green">Green</option>
                                        <option value="pink">Pink</option>
                                        <option value="red">Red</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <p class="text-info">Total (<b class="text-danger">{{count($results)}}</b>) items</p>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Imei</th>
                                <th>Model</th>
                                <th>Storage</th>
                                <th>Color</th>
                                <th>Color Folder</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $product)
                                <tr>
                                    <td>{{$product->imei}}</td>
                                    <td>{{$product->model}}</td>
                                    <td>{{$product->storage}}</td>
                                    <td>{{$product->color}}</td>
                                    <td>{{$product->color_folder}}</td>
                                    <td title="{{$product->c_date}}">{{date('M-d-Y', strtotime($product->c_date))}}</td>
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

        var date= JSON.parse('<?php  ?>')
        var total_imei= JSON.parse('<?php  ?>')

        var canvas = document.getElementById('myChart');

        var data = {

            labels: date,
//                        labels: ["2008", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            datasets: [
                {
                    label: "Testing report",
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
            /*options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            parser: 'YYYY-MM-DD',
                            unit: 'day',
                            displayFormats: {
                                day: 'DD-MM-YYYY'
                            },
                            min: '2017-10-02 18:43:53',
                            max: '2017-10-09 18:43:53'
                        },
                        ticks: {
                            source: 'data'
                        }
                    }]
                }
            }*/
        })


        //    chart2

        var date2= JSON.parse('<?php ?>')
        console.log(date2)
        var total_imei2= JSON.parse('<?php ?>')
        console.log(total_imei2)
        var canvas2 = document.getElementById('myChart2');

        var data2 = {

            labels: date2,
            lineColor: "red",
//                        labels: ["2008", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            datasets: [
                {

                    label: "defect rate report",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "#e89d81",
                    borderColor: "#e83131",
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
                    data: total_imei2
                }
            ]
        };

        function adddata(){
            myLineChart.data.datasets[0].data[7] = 60;
            myLineChart.data.labels[7] = "Newly Added";
            myLineChart.update();
        }

        var option2 = {
            showLines: true
        };
        var myLineChart = Chart.Line(canvas2, {
            data: data2,
            options: option2,
        })


    </script>
@endpush
