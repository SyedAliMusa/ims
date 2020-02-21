@extends('layouts.customer.app')

@section("title")
    Testing
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Testing > <span class="text-danger">{{$products[0]->createdby}}</span></h3>
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
													<span>New Testing</span>
												</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="col-md-3 offset-9">
                        <form action="{{url()->current()}}" method="get" role="form">
                            <div class="form-group">
                                <input type="hidden" name="submit">
                                <input type="text" title="Search | Lot | ASIN | Brand | Model "
                                       class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search IMEI" width="25%" autofocus>
                                {{--<small id="helpId" class="form-text text-muted text-danger">Search | Lot | ASIN | Brand | Model |</small>--}}
                            </div>
                        </form>
                    </div>
                    <!--begin: Datatable -->
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Storage</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Test_Count</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->inventory->lot->brand->name}}</td>
                                <td>{{$product->inventory->lot->model}}</td>
                                <td>{{$product->inventory->lot->network->name}}</td>
                                <td>{{$product->inventory->lot->color}}</td>
                                <td>{{$product->inventory->lot->storage->name}}</td>
                                <td>{{$product->inventory->imei}}</td>
                                <td>{{$product->inventory->category->name}}</td>
                                <td>
                                    @if ($product->inventory->status == 1)
                                        <span class="m-badge  m-badge--success m-badge--wide"> Available </span>
                                    @else
                                        <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>
                                    @endif
                                </td>
                                <td>{{$product->count}}</td>
                                <td>{{$product->user->name}}</td>
                                <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                <td>
                                    <form action="{{URL::to('testing/' . $product->id).'/edit'}}" method="get">
{{--                                        {{csrf_field()}}--}}
                                        {{--<input type="hidden" name="_method" value="POST">--}}
                                        <button type="submit"  class="btn btn-outline-primary">
                                            view problem
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="First group">
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
                    </div>
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