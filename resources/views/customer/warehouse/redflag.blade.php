@extends('layouts.customer.app')

@section("title")
    Warehouse
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Red-Flag</h3>
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
                            <div class="col-md-12">
                                <form method="get" action="{{url()->current()}}"class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text"  class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                                                <input type="text"  class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">
                                                <select class="form-control" name="issued_to_for_report" >
                                                    <option value="">select Tester</option>
                                                    <option value="Tester">Tester</option>
                                                    <option value="Refurbisher">Refurbisher</option>
                                                    <option value="Reinel">Reinel</option>
                                                </select>
                                                <button type="submit"
                                                        class="btn btn-outline-warning">Get Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a class="btn btn-brand" href="{{route('ExportRedFlag')}}?from={{request()->input('from')}}&to={{request()->input('to')}}&issued_to_for_report={{request()->input('issued_to_for_report')}}">Export Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <p class="text-info">Total (<b class="text-danger">{{count($products)}}</b>) items in Red-Flag</p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Model</th>
                            <th>Storage</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Issued To</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->inventory->lot['model']}}</td>
                                <td>{{$product->inventory->lot['storage']['name']}}</td>
                                <td>{{$product->inventory->lot['color']}}</td>
                                <td>{{$product->inventory->imei}}</td>
                                <td>{{$product->inventory->category->name}}</td>
                                <td>{{$product->issued_to}}</td>
                                <td title="{{$product->created_at}}">{{date('M-d-Y', strtotime($product->created_at))}}</td>
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