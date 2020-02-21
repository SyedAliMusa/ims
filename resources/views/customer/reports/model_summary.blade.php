@extends('layouts.customer.app')

@section("title")
    Model Summary Report
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
                    <h3 class="m-subheader__title ">Model Summary Report <span class="text-danger"></span></h3>
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
                    <div class="row" style="margin: 2%">
                        <div class="col-md-10">
                            <form class="form-inline" action="{{url()->current()}}" method="post" role="form" id="form_validation">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <select class="form-control" name="brand" id="selected_brand"  onchange="getModelByBrand()" >
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" required onchange="getNetworkStorageColorCategoryByModel()" >
                                        <option value="" selected> model</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="network" >
                                        <option value="" selected> network</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="storage" >
                                        <option value="" selected> storage</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="color" >
                                        <option value="" selected> color</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="category" >
                                        <option value="" selected> category</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                <button type="submit" class="btn btn-warning">Reset all</button>
                            </form>
                        </div>
                    </div>
                    @if (request()->input('brand') and request()->input('model'))
                        <div class="row">
                            <div class="col-md-12">
                                {{--                                <span class="text-danger">{{\App\Models\Brand::find(request()->input('brand'))->name}} </span> >--}}
                                {{--<span class="text-primary">{{request()->input('model')}}</span>--}}

                            </div>
                        </div>
                @endif
                <!--begin: Datatable -->
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Model</th>
                            <th>Network</th>
                            <th>storage</th>
                            <th>Color</th>
                            <th>Bought Quantity</th>
                            <th>Inventory Quantity</th>
                            <th>Dispatch</th>
                            <th>Available</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->model}}</td>
                                <td>{{$product->network->name}}</td>
                                <td>{{$product->storage->name}}</td>
                                <td>{{$product->color}}</td>
                                <td>{{$product->asin_total_quantity}}</td>
                                <td>{{$product->inventory_quantity}}</td>
                                <td class="text-danger">{{$product->dispatched}}</td>
                                <td class="text-success">{{$product->inventory_quantity - $product->dispatched}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>

    </div>
@stop
@push('scripts')
    <script>
        function getModelByBrand() {
            var brand_id =  $('select[name=brand]').val();
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id
                },
                url: "{{route('model_by_brand')}}",
                success: function (data) {
                    // console.log(data)
                    $('select[name=model]').html('<option value="" selected> model </option>')
                    $.each(data, function (index, value) {
                        $('select[name=model]').append('<option value=' + value.model + '>' + value.model + '</option>')
                    });
                }
            });
        }
        function getNetworkStorageColorCategoryByModel() {
            var brand_id =  $('select[name=brand]').val();
            var model =  $('select[name=model]').val();
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model
                },
                url: "{{route('network_storage_color_cat_by_brand')}}",
                success: function (data) {
                    console.log(data)
                    $('select[name=network]').html('<option value="" selected> network</option>')
                    $('select[name=storage]').html('<option value="" selected> storage</option>')
                    $('select[name=color]').html('<option value="" selected> color</option>')
                    $('select[name=category]').html('<option value="" selected> category</option>')
                    $.each(data.models, function (index, value) {
                        $('select[name=model]').append('<option value=' + value.model + '>' + value.model + '</option>')
                    });
                    $.each(data.networks, function (index, value) {
                        $('select[name=network]').append('<option value=' + value.id + '>' + value.name + '</option>')
                    });
                    $.each(data.storage, function (index, value) {
                        $('select[name=storage]').append('<option value=' + value.id + '>' + value.name + '</option>')
                    });
                    $.each(data.colors, function (index, value) {
                        $('select[name=color]').append('<option value=' + value.color + '>' + value.color + '</option>')
                    });
                    $.each(data.categories, function (index, value) {
                        $('select[name=category]').append('<option value=' + value.id + '>' + value.name + '</option>')
                    });
                }
            });
        }


    </script>
@endpush