@extends('layouts.customer.app')

@section("title")
    Tester's Performance Report
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
                    <h3 class="m-subheader__title ">    Tester's Performance Report                    </h3>
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
                                <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                    <div class="form-group">
                                        <input type="hidden" name="count_testing_by_imei" value="123">
                                        Find Tester(s) By IMIE &nbsp; <input type="text" class="form-control" name="imei" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required autofocus="autofocus">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-default"> filter </button>
                                    </div>
                                </form>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    @if ($testings)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>###</th>
                                        <th>Tested By</th>
                                        <th>Tested At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($testings as $key=>$testing)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$testing->user->name}}</td>
                                            <td title="{{$testing->created_at}}">{{ date('M-d-Y', strtotime($testing->created_at))}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <br>
                                <br>
                            </div>
                        </div>
                    @endif
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

                                    <select class="form-control" name="tester_id" id="tester" >
                                        <option value="">select tester</option>
                                        @foreach(\App\User::all() as $tester)
                                            @if ($tester->is_deleted !=1 && $tester->account_type == 'tester')
                                                <option value="{{$tester->id}}">{{$tester->name}} &nbsp;&nbsp;|: {{$tester->account_type}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="{{route('report.tester.export')}}" method="get" class="form-inline" role="form">
                                <input type="hidden" name="from" value="{{request()->input('from')}}">
                                <input type="hidden" name="to" value="{{request()->input('to')}}">
                                <input type="hidden" name="tester_id" value="{{request()->input('tester_id')}}">
                                <button type="submit" class="btn btn-warning">Export CSV</button>
                            </form>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            @if(count($testing_performance) > 0)
                                <?php
                                $date = array();
                                $total_imei = array();
                                ?>
                                @foreach($testing_performance as $product)
                                    <?php
                                    array_push($date ,date('M-d-Y', strtotime($product->date)));
                                    array_push($total_imei,$product->total_imei);
                                    ?>
                                @endforeach
                            @else
                                <?php $total_imei = ['0'];
                                $date = []?>
                            @endif
                            <canvas id="myChart" width="900" height="400" style="    width: 1000px !important;
                                    height: 400px !important;"></canvas>
                        </div>
                        <div class="col-md-6">
                            @if(count($testing_defeats) > 0)
                                <?php
                                $date2 = array();
                                $total_imei2 = array();
                                ?>
                                @foreach($testing_defeats as $product)
                                    <?php
                                    array_push($date2 ,date('M-d-Y', strtotime($product->date)));
                                    array_push($total_imei2,$product->total_imei);
                                    ?>
                                @endforeach
                            @else
                                <?php $total_imei2 = ['0'];
                                $date2 = []?>
                            @endif
                            <canvas id="myChart2" width="900" height="400" style="    width: 1000px !important;
                                    height: 400px !important;"></canvas>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-4 offset-1">
                            <h4>Testing Report</h4>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Sold</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($testing_performance as $product)
                                    <tr>
                                        <td><a href="{{url()->current()}}?from={{request()->input('from')}}&to={{request()->input('to')}}&tester_id={{request()->input('tester_id')}}&date={{$product->date}}">{{ date('M-d-Y', strtotime($product->date))}}</a></td>
                                        <td class="text-danger">{{$product->total_imei}}</td>
                                    </tr>
                                    @if (count($testing_data) > 0)
                                        @if (date('Y-m-d', strtotime($testing_data[0]->created_at)) == $product->date)
                                            @foreach($testing_data as $product)
                                                <tr style="border: 1px solid">
                                                    <td>{{$product->inventory->imei}}</td>
                                                    <td>{{$product->inventory->lot->model}}</td>
                                                    <td>{{$product->inventory->lot->color}}</td>
                                                    <td>{{$product->inventory->category->name}}</td>
                                                    @if(count(App\Problems::where('testing_id','=',(App\Testing::where('inventory_id','=',$product->inventory->id)->orderByDesc('id')->first()->id))->where('status','=',0)->get()) < 1)
                                                        <td class="text-success"> Pass </td>
                                                    @else
                                                        <td class="text-danger"> Fail </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 offset-1">
                            <h4>Defect Rate Report</h4>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Sold</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($testing_defeats as $product)
                                    <tr>
                                        <td><a href="{{url()->current()}}?from={{request()->input('from')}}&to={{request()->input('to')}}&tester_id={{request()->input('tester_id')}}&date={{$product->date}}">{{ date('M-d-Y', strtotime($product->date))}}</a></td>
                                        <td class="text-danger">{{$product->total_imei}}</td>
                                    </tr>
                                    @if (count($testing_data_def) > 0)
                                        {{--                                        {{$testing_data_def[0]}}--}}
                                        @if (date('Y-m-d', strtotime($testing_data_def[0]->created_at)) == $product->date)
                                            @foreach($testing_data_def as $product)
                                                <tr style="border: 1px solid">
                                                    <td>{{$product->inventory->imei}}</td>
                                                    <td>{{$product->inventory->lot->model}}</td>
                                                    <td>{{$product->inventory->lot->color}}</td>
                                                    <td>{{$product->inventory->lot->storage->name}}</td>
                                                    <td>{{$product->inventory->category->name}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
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

        var date2= JSON.parse('<?php echo json_encode($date2); ?>')
        console.log(date2)
        var total_imei2= JSON.parse('<?php echo json_encode($total_imei2); ?>')
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