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
                    <h3 class="m-subheader__title ">Release stock from warehouse</h3>
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
                    <h4 class="text-danger">{{session('deleted')}}</h4>
                    <div class="row" style="margin: 3%;">
                        {{--<div class="col-md-7 offset-1">
                            <form method="post" action="{{route('warehouse.store')}}" role="form" id="form_verify_imei">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <b class="text-primary">Issued To </b>
                                            <select class="form-control" name="issued_to" required>
                                                <option value="">  </option>
                                                @if (request()->input('issued_to'))
                                                    <option value="{{request()->input('issued_to')}}" selected>{{request()->input('issued_to')}}</option>
                                                @endif
                                                <option value="Tester">Tester</option>
                                                <option value="Refurbisher">Refurbisher</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group on_error">
                                            <b class="text-primary">Issue "IMEI" From Warehouse</b>
                                            <input type="hidden" name="submit">
                                            <input type="text" title="Verify imei "
                                                   class="form-control input_border" name="imei" placeholder="Search IMEI" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%" autofocus autocomplete="off">
                                            <small id="imei_exist" class="text-danger">{{session('fail')}}</small>
                                            <small id="imei_exist" class="text-success">{{session('success')}}</small>
                                            <small id="imei_exist" class="text-primary">{{session('already_verified')}}</small>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>--}}
                        <div class="col-md-12">
                            <form method="get" action="{{url()->current()}}"class="form-inline">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text"  class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                                            <input type="text"  class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">
                                            <select class="form-control select_tags" name="colors">
                                                <option value="">Select Color Folder</option>
                                                <option value="black">Black</option>
                                                <option value="purple">Purple</option>
                                                <option value="blue">Blue</option>
                                                <option value="green">Green</option>
                                                <option value="pink">Pink</option>
                                                <option value="red">Red</option>
                                            </select>
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
                    <div class="row" style="margin: 3%;">
                        @if(count($products) > 0)

                            <div class="col-md-12">
                                <p class="text-info">Total ({{$products->total}}) items Stocked Out</p>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Network</th>
                                        <th>Color</th>
                                        <th>Imei</th>
                                        <th>Category</th>
                                        <th>Color Folder</th>
                                        <th>Status</th>
                                        <th>Issued To</th>
                                        <th>Added By</th>
                                        <th>Date</th>
                                        {{--<th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{$product->inventory->lot->model}}</td>
                                            <td>{{$product->inventory->lot->network->name}}</td>
                                            <td>{{$product->inventory->lot->color}}</td>
                                            <td>{{$product->inventory->imei}}</td>
                                            <td>{{$product->inventory->category->name}}</td>
                                            <td>{{$product->color_folder}}</td>
                                            <td>
                                                <span class="m-badge  m-badge--info m-badge--wide">In Progress</span>
                                            </td>
                                            <td>{{$product->issued_to}}</td>
                                            <td>{{$product->user->name}}</td>
                                            <td title="{{$product->created_at}}">{{date('M-d-Y', strtotime($product->created_at))}}</td>
                                            <td>
                                                <form action="{{URL::to('warehouse/' . $product->id)}}" method="post">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit"  onclick="return confirm('Are you sure?')"  class="btn btn-outline-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if ($products->total > 19)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-group" role="group" aria-label="First group">
                                                @if ($products->previousPageUrl())
                                                    <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->previousPageUrl()}}">Previous</a>
                                                @endif
                                                @if ($products->nextPageUrl())
                                                    <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->nextPageUrl()}}">Next</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
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
    </script>
@endpush