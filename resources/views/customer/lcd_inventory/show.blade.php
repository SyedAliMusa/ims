@extends('layouts.customer.app')

@section("title")
    Inventory
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
                    <h3 class="m-subheader__title ">Inventory >
                        <span class="text-danger">
                            @if (count($products) > 0)
                                {{$products[0]->lot->lot_id}}
                            @endif
                        </span>
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
                        <div class="col-md-3 offset-9">
                            <form action="{{url()->current()}}" method="get" role="form">
                                <div class="form-group">
                                    <input type="hidden" name="submit">
                                    <input type="text" title="Search | Lot | ASIN | Brand | Model "
                                           class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search" width="25%" autofocus>
                                    {{--<small id="helpId" class="form-text text-muted text-danger">Search | Lot | ASIN | Brand | Model |</small>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                    <p class="text-info">Total ({{$products->total}}) "Inventory"s</p>
                    <!--begin: Datatable -->
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Asin</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Storage</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->lot->asin}}</td>
                                <td>{{$product->lot->brand->name}}</td>
                                <td>{{$product->lot->model}}</td>
                                <td>{{$product->lot->network->name}}</td>
                                <td>{{$product->lot->color}}</td>
                                <td>{{$product->lot->storage->name}}</td>
                                <td>{{$product->imei}}</td>
                                <td>{{$product->category->name}}</td>
                                <td>
                                    @if ($product->status == 1)
                                        <span class="m-badge  m-badge--success m-badge--wide">Available</span>
                                    @else
                                        <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>
                                    @endif
                                </td>
                                <td>{{$product->user->name}}</td>
                                <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                <td>
                                    <form action="{{URL::to('inventory/' . $product->id)}}" method="post">
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