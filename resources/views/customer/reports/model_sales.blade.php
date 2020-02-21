@extends('layouts.customer.app')

@section("title")
    Model Sales Report
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
                    <h3 class="m-subheader__title ">    Model Sales Report                    </h3>
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
                        <div class="col-md-10">
                            <form action="{{url()->current()}}" method="post" class="form-inline" role="form">
                                {{csrf_field()}}
                                <div class="form-group">

                                    <input type="text" required class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                                    <input type="text" required class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">


                                    <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" required>
                                        <option value="" selected> models </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>

                    <div class="row" style="    padding: 0% 10%;">
                        <div class="col-md-12">
                            <?php
                            $models = array();
                            $total_sold = array();
                            ?>
                            @foreach($products as $product)
                                <?php
                                array_push($models ,$product->model);
                                array_push($total_sold,$product->sold);
                                ?>
                            @endforeach
                            <canvas id="myChart" style="max-width: 1000px;
    max-height: 300px;"></canvas>
                        </div>
                    </div>
                    @if (count($products) > 0)
                        <div class="row" style="    padding: 0% 10%;">
                            <div class="col-md-12">
                                <table id="example" class="table table-hover table-striped table-bordered"  style="width:100%">
                                    <thead>
                                    <tr>
                                        <td >Model</td>
                                        <td >Quantity Sold</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $value)
                                        <tr>
                                            <td>{{$value->model}}</td>
                                            <td class="text-danger">{{$value->sold}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endif
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
        function getModelByBrand() {
            var brand_id =  $('select[name=brand_id]').val();
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id
                },
                url: "{{route('model_by_brand')}}",
                success: function (data) {
                    // console.log(data)
                    $('select[name=model]').html('<option value="" selected> model </option>')
                    var data_first_letter_array = [];
                    $.each(data, function (index, value) {
                        var v = value.model.charAt(0)
                        data_first_letter_array.push(v);
                    });
                    data_first_letter_array = $.unique(data_first_letter_array)
                    console.log(data_first_letter_array)
                    $.each(data_first_letter_array, function (index, value) {
                        $('select[name=model]').append('<option style="background-color: #34bfa3;padding: 11px;border-radius: 50%;color: white;" value=' + value + '>' + value + '</option>')
                    });
                }
            });
        }
        //pie chart added
        var models= JSON.parse('<?php echo json_encode($models); ?>')
        var total_sold= JSON.parse('<?php echo json_encode($total_sold); ?>')

        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: models,
                datasets: [
                    {
                        label: '# Original Quantity',
                        data: total_sold,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });


    </script>
@endpush