@extends('layouts.customer.app')

@section("title")
    Lot | Asin | Edit
@endsection
@push("css")
    {{--include internal css--}}
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Lot > Asin > Edit</h3>
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
                    @if (session('success'))
                        <h4 class="text-success">{{session('success')}}</h4>
                    @else
                        <h4 class="text-warning">{{session('quantity_exceed')}}</h4>
                    @endif
                    <form action="{{route('lots.update',$product->id)}}" method="post" class="form-horizontal" id="lot_insert_form" role="form">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row">
                            <div class="col-md-4 offset-1">
                                <div class="form-group margin-0">
                                    <label for="usr">Lot_ID</label>
                                    <input type="text" class="form-control" name="lot_id" id=""  value="{{$product->lot_id}}" required disabled>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" name="brnad" id=""  value="{{$product->brand->name}}" required disabled>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" name="model" id="" value="{{$product->model}}" disabled required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <input type="text" class="form-control" name="network" id="" value="{{$product->network->name}}" disabled required>
                                </div>
                            </div>
                            <div class="col-md-4  offset-1">
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" name="color" id=""  value="{{$product->color}}" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <select class="form-control" name="storage_id" id="select_storage" required>
                                        <option value="" selected></option>
                                        <option value="" selected></option>
                                        @foreach($storages as $brand)
                                            @if ($brand->id == $product->storage_id)
                                                <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                                            @else
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" id=""  value="{{$product->asin_total_quantity}}" required>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">ASIN#</label>
                                    <div id="append">
                                        <input type="text" class="form-control" name="asin" id=""  value="{{$product->asin}}" required disabled>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-warning" style="width: 100%"> Save </button>
                            </div>
                            <div class="col-md-1" style="padding-top: 1%;">
                                <span id="imei_success" style="color: blue"></span>
                            </div>
                        </div>
                    </form>
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

    </script>
@endpush