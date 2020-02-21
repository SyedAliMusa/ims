@extends('layouts.customer.app')

@section("title")
    Inventory | Create
@endsection
@push("css")
    <style>
        .hide{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">LCD Inventory | Print Barcode</h3>
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
                    <div class="container-fluid">
                        <form class="form-horizontal"  action="{{route('lcd_inventory.barcode_generator')}}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Brand</label>
                                        <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                            <option value=""> brands </option>
                                            @foreach(\App\Brand::all() as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group margin-0">
                                        <label for="pwd">Model</label>
                                        <select class="form-control model_selector" name="model" id="selected_model" required>
                                            <option value="" selected> models </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group margin-0">
                                        <label for="usr">Category</label>
                                        <select class="form-control" name="category" required>
                                            <option value="" selected></option>
                                            @foreach(\App\Category::all() as $item)
                                                <option value="{{$item->name}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group margin-0">
                                        <label for="usr">Quantity To Print</label>
                                        <input type="text" class="form-control" name="quantity" id="" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" minlength="14" value=""  required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="type" id="type" class="form-control hide">
                                            <option value="code128" selected>Code39</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="orientation" class="form-control hide" required>
                                            <option value="horizontal" selected="selected">Horizontal</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="number" name="size" id="size" class="form-control hide" min="10" max="400" step="10" value="20" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="print" id="print" class="form-control hide" required>
                                            <option value="true" selected="selected">True</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-12">
                                    <input type="submit" name="submit" class="btn btn-success text-center form-controll" id="" value="Generate Barcode">
                                </div>
                            </div>

                        </form>
                    </div>

                    {{--<div class="row">
                        <div class="col-md-12">
                            <img alt="Coding Sips" src="{{route('lcd_inventory.barcode')}}?text=Coding-sips-item-no-786&print=true" />
                        </div>
                    </div>--}}
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
    <script src="{{asset('customer/assets/demo/default/custom/crud/forms/widgets/select2.js')}}" type="text/javascript"></script>
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
