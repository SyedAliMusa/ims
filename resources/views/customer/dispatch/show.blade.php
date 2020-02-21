@extends('layouts.customer.app')

@section("title")
    Dispatch
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
                    <h3 class="m-subheader__title ">Dispatch > <span class="text-danger">{{$products[0]->lot->lot_id}}</span></h3>
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
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#" class="btn btn-accent m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
												<span>
													<i class="la la-plus"></i>
													<span>New Dispatch</span>
												</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group">
                        <input type="text"
                               class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search">
                        <small id="helpId" class="form-text text-muted text-danger">Search | IMEI | Brand | Model | Color | Storage | Category |</small>
                    </div>
                    <!--begin: Datatable -->
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>LotID</th>
                            <th>Asin</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->lot->lot_id}}</td>
                                <td>{{$product->lot->asin}}</td>
                                <td>{{$product->lot->brand->name}}</td>
                                <td>{{$product->lot->model}}</td>
                                <td>{{$product->lot->netwrok_id}}</td>
                                <td>{{$product->lot->color}}</td>
                                <td>{{$product->imei}}</td>
                                <td>{{$product->category->name}}</td>
                                <td>
                                    @if ($product->status == 0)
                                        <span class="m-badge  m-badge--success m-badge--wide">Available</span>
                                    @else
                                        <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>
                                    @endif
                                </td>
                                <td>{{$product->user->name}}</td>
                                <td>{{$product->created_at}}</td>
                                <td>
                                    <a  onclick="return confirm('Are you sure?')"  class="btn btn-danger" href="{{URL::to('inventory/' . $product->id)}}">
                                        Delete
                                    </a></td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>

    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {

        })

    </script>
@endpush