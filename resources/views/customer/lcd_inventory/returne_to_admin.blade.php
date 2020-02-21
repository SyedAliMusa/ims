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
                    <h3 class="m-subheader__title ">LCD Returns</h3>
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
                    <div class="row">
                        {{--release--}}
                        <div class="col-md-12" style="    background: ghostwhite;     border-right: 10px solid white;">
                            <div class="row" style="margin: 3%;">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Barcode</th>
                                            <th>Category</th>
                                            <th>Returned By</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (auth()->user())
                                            @foreach(\App\LcdIssuedToStatus::where('status','=', '2')->get() as $product)
                                                <tr>
                                                    <td>{{$product->lcd_issued_to->lcd_inventory->brand_id}}</td>
                                                    <td>{{$product->lcd_issued_to->lcd_inventory->modal}}</td>
                                                    <td>{{$product->lcd_issued_to->lcd_inventory->category_id}}</td>
                                                    <td>{{$product->lcd_issued_to->lcd_inventory->barcode}}</td>
                                                    <td>{{$product->user->name}}</td>
                                                    <td>{{ date('M-d-Y', strtotime($product->created_at))}}</td>
                                                    <td><a href="">Not Verified</a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    <script>

    </script>
@endpush