@extends('layouts.customer.app')

@section("title")
    Inventory
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        {{--<div class="m-subheader ">--}}
            {{--<div class="d-flex align-items-center">--}}
                {{--<div class="mr-auto">--}}
                    {{--<h3 class="m-subheader__title ">LCD Inventory</h3>--}}
                {{--</div>--}}
                {{--<div>--}}
                    {{--<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">--}}
                        {{--<span class="m-subheader__daterange-label">--}}
										{{--<span class="m-subheader__daterange-title"></span>--}}
										{{--<span class="m-subheader__daterange-date m--font-brand"></span>--}}
                        {{--</span>--}}
                        {{--<a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">--}}
										{{--<i class="la la-angle-down"></i>--}}
                        {{--</a>--}}
                    {{--</span>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="m-content">--}}
            {{--<div class="m-portlet m-portlet--mobile">--}}
                {{--<div class="m-portlet__head">--}}
                    {{--<div class="m-portlet__head-caption">--}}
                        {{--<div class="m-portlet__head-title">--}}
                            {{--<h3 class="m-portlet__head-text">All--}}
                                    {{--({{$proCount}})--}}
                            {{--</h3>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="m-portlet__head-tools">--}}
                        {{--<ul class="m-portlet__nav">--}}
                            {{--<li class="m-portlet__nav-item">--}}
                                {{--<a href="{{route('lcd_inventory.create')}}" class="btn btn-accent m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">--}}
												{{--<span>--}}
													{{--<i class="la la-plus"></i>--}}
													{{--<span>New LCD Inventory</span>--}}
												{{--</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="m-portlet__nav-item">--}}

                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="m-portlet__body">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-6">--}}
                            {{--<form action="{{url()->current()}}" method="get" class="form-inline" role="form">--}}
                                {{--<div class="form-group">--}}
                                    {{--<input type="hidden" name="filter" value="true">--}}
                                    {{--<select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">--}}
                                        {{--<option value=""> brands </option>--}}
                                        {{--@foreach($brands as $brand)--}}
                                            {{--<option value="{{$brand->id}}">{{$brand->name}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<select class="form-control model_selector" name="model" id="selected_model" required>--}}
                                        {{--<option value="" selected> models </option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3 offset-3">--}}
                            {{--<form action="{{url()->current()}}" method="get" role="form">--}}
                                {{--<div class="form-group">--}}
                                    {{--<input type="hidden" name="submit">--}}
                                    {{--<input type="text" title="Search inventory "--}}
                                           {{--class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search" width="25%" autofocus>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--begin: Datatable -->--}}
                    {{--<p><b id="replacedWithChecked"></b></p>--}}
                    {{--<table class="table table-striped table-bordered table-hover">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Brand</th>--}}
                            {{--<th>Model</th>--}}
                            {{--<th>Category</th>--}}
                            {{--<th>Color</th>--}}
                            {{--<th>Barcode</th>--}}
                            {{--<th>Status</th>--}}
                            {{--<th>Added By</th>--}}
                            {{--<th>Date Added</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($products as $product)--}}
                            {{--<tr>--}}
                                {{--<td>{{$product->brand->name}}</td>--}}
                                {{--<td>{{$product->modal}}</td>--}}
                                {{--<td>{{$product->category->name}}</td>--}}
                                {{--<td>{{$product->color}}</td>--}}
                                {{--<td>{{$product->barcode}}</td>--}}
                                {{--@if ($product->status == 4)--}}
                                    {{--<td>Available</td>--}}
                                    {{--@else--}}
                                    {{--<td>{{config('general.lcd_inventory_status.'.$product->status)}}</td>--}}
                                {{--@endif--}}
                                {{--<td>{{$product->user->name}}</td>--}}
                                {{--<td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->updated_at))}}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div class="btn-group" role="group" aria-label="First group">--}}
                                {{--<div class="btn-group" role="group" aria-label="First group">--}}
                                    {{--@if ($products->previousPageUrl())--}}
                                        {{--<a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->previousPageUrl()}}">Previous</a>--}}
                                    {{--@endif--}}
                                    {{--@if ($products->nextPageUrl())--}}
                                        {{--<a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->nextPageUrl()}}">Next</a>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<!-- END EXAMPLE TABLE PORTLET-->--}}
        {{--</div>--}}
<div align="center" style="height: 100vh; position: relative;">
    <div style="position: absolute; top:40%; left:40%;">
        <a href="{{route('lcd_inventory.print_barcode')}}" class="btn btn-brand m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air" style="padding: 40px 100px;">
												<span>
													<i class="la la-plus" style="font-size: xx-large"></i>
													<span style="font-size: xx-large">Print Barcode</span>
												</span>
        </a>
    </div>
</div>

        {{--<div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">--}}
            {{--<div class="m-alert__icon">--}}
                {{--<i class="flaticon-exclamation m--font-brand"></i>--}}
            {{--</div>--}}
            {{--<div class="m-alert__text">--}}
                {{--DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.--}}
                {{--For more info see--}}
            {{--</div>--}}
        {{--</div>--}}

    </div>
@stop
@push('scripts')
    <script>
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
                    $.each(data, function (index, value) {
                        $('select[name=model]').append('<option value=' + value.model + '>' + value.model + '</option>')
                    });
                }
            });
        }


    </script>
@endpush