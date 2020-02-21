@extends('layouts.customer.app')

@section("title")
    Search
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .input_border{
            border-width: 2px;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Search "result"s for >
                        <span class="text-danger"> {{request()->input('q')}}  </span>
                        <span> &nbsp; Avaialble(<b class="text-primary">{{$products->available}}</b>) + Sold(<b class="text-danger">{{count($products) - $products->available}}</b>) = {{count($products)}}  </span>
                    </h3>
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
                    <!--begin: Datatable -->
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Asin</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Storage</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Status</th>
                            {{--<th>Tracking No</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->lot->asin}}</td>
                                <td>{{$product->lot->brand->name}}</td>
                                <td>{{$product->lot->model}}</td>
                                <td>{{$product->lot->network->name}}</td>
                                <td>{{$product->lot->storage->name}}</td>
                                <td>{{$product->lot->color}}</td>
                                <td>{{$product->imei}}</td>
                                <td>{{$product->category->name}}</td>
                                <td>
                                    @if ($product->status == 1)
                                        <?php $is_in_progress =  \App\WarehouseInOut::where('inventory_id','=',$product->id)->first() ?>
                                        @if ($is_in_progress)
                                            <span class="m-badge  m-badge--info m-badge--wide">In Progress</span>
                                        @else
                                            <span class="m-badge  m-badge--success m-badge--wide">Available</span>
                                        @endif
                                    @else
                                        <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>
                                    @endif
                                </td>
                                {{--                                <td>{{$product->dispatch->tracking}}</td>--}}
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
    <script>
        $(document).ready(function () {

        })

    </script>
@endpush