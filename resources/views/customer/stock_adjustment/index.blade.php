@extends('layouts.customer.app')

@section("title")
    Stock-Adjustment
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
                    <h3 class="m-subheader__title ">Stock-Adjustment</h3>
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
                        <div class="col-md-6">
                            <form action="" method="get" class="form-inline" role="form">
                                <div class="form-group">
                                    <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" required>
                                        <option value="" selected> models </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    @if (count($products) < 1)
                        <div class="col-md-4 offset-4">
                            @if (request()->input('brand_id'))
                                <b class="text-danger">{{\App\Brand::find(request()->input('brand_id'))->name}} </b> >
                                <b class="text-primary">{{request()->input('model')}}</b>
                                <h4 class="text-warning">Stock is not available in this model</h4>
                            @endif
                        </div>
                    @else
                        <div class="row" style="margin: 3%;">
                            <div class="col-md-4 offset-4">
                                <form method="get" action="{{route('stock-adjustment.verify')}}" role="form" id="form_verify_imei">
                                    @if (request()->input('brand_id'))
                                        <b class="text-danger">{{\App\Brand::find(request()->input('brand_id'))->name}} </b> >
                                        <b class="text-primary">{{request()->input('model')}}</b>
                                    @endif

                                    <div class="form-group on_error">
                                        <input type="hidden" name="submit">
                                        <input type="hidden" name="brand_id" value="{{request()->input('brand_id')}}">
                                        <input type="hidden" name="model" value="{{request()->input('model')}}">
                                        <input type="text" title="Verify imei "
                                               class="form-control input_border" name="imei" placeholder="Search IMEI" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%" autofocus>
                                        <small id="imei_exist" class="text-danger">{{session('fail')}}</small>
                                        <small id="imei_exist" class="text-success">{{session('success')}}</small>
                                        <small id="imei_exist" class="text-primary">{{session('already_verified')}}</small>

                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="row" style="margin: 3%;">
                    @if(count($products) > 0)

                        <div class="col-md-4 offset-1">
                            <table id="available" class="table table-hover table-bordered table-striped table-sm"  style="width:100%">
                                <thead>
                                <tr>
                                    <td style="width: 150px;">Available Stock</td>
                                    <td style="width: 150px;">Total (<b>{{$products->total()}}</b>)</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $value)
                                    <tr id="verified{{$value->imei}}">
                                        <td>{{$value->imei}}</td>
                                        <td>
                                            <form action="{{URL::to('inventory/' . $value->id)}}" method="post">
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
                            @if ($products->total() > 29)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group" role="group" aria-label="First group">
                                        <a class="m-btn btn btn-outline-brand btn-sm"  href=" {{$products->previousPageUrl()}}&brand_id={{request()->input('brand_id')}}&model={{request()->input('model')}}" role="button"> Previous </a>
                                        <a class="m-btn btn btn-outline-brand btn-sm"  href=" {{$products->nextPageUrl()}}&brand_id={{request()->input('brand_id')}}&model={{request()->input('model')}}" role="button"> Next </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if(count($verified) > 0)
                        <div class="col-md-5 offset-1">
                            <table id="verified" class="table table-hover table-bordered table-striped table-sm"  style="width:100%">
                                <thead>
                                <tr>
                                    <td style="width: 150px;">Verified Stock Total (<b>{{$verified->total()}}</b>)</td>
                                </tr>
                                </thead>
                                <tbody id="imei_success">
                                <?php $count = 1 ?>
                                @foreach($verified as $value)
                                    @if ($count == 1)
                                        <?php $count = 2 ?>
                                        <tr style="border: 2px solid;color: blue;" class="table-sm">
                                            <td>
                                                <form action="{{URL::to('inventory/change/category/' . $value->id)}}" method="get">
                                                    <div class="row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">{{$value->imei}} ({{$value->category->name}})</label>
                                                        <div class="col-sm-6">
                                                            <select class="form-horizontal btn-sm" name="category_id" required>
                                                                <option value="" selected></option>
                                                                @foreach(\App\Category::all() as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <button type="submit"   class="btn btn-outline-success btn-sm">
                                                                save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <form action="{{URL::to('inventory/change/category/' . $value->id)}}" method="get">
                                                    <div class="row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">{{$value->imei}} ({{$value->category->name}})</label>
                                                        <div class="col-sm-6">
                                                            <select class="form-horizontal btn-sm" name="category_id" required>
                                                                <option value="" selected></option>
                                                                @foreach(\App\Category::all() as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <button type="submit"   class="btn btn-outline-success btn-sm">
                                                                save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>

                            </table>
                            @if ($products->total() > 29)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group" role="group" aria-label="First group">
                                            <a class="m-btn btn btn-outline-brand btn-sm"  href=" {{$products->previousPageUrl()}}&brand_id={{request()->input('brand_id')}}&model={{request()->input('model')}}" role="button"> Previous </a>
                                            <a class="m-btn btn btn-outline-brand btn-sm"  href=" {{$products->nextPageUrl()}}&brand_id={{request()->input('brand_id')}}&model={{request()->input('model')}}" role="button"> Next </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
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

        function getChangeCategory(imei) {
            alert($('select[name=category'+imei+']').val())
            $.ajax({
                type: "GET",
                data: {
                    imei: imei,
                    category_id: $('select[name=category'+imei+']').val(),
                },
                url: "{{route('change_category')}}",
                success: function (data) {
                    console.log(data)
                    $('#category_saved'+imei).append('<tr><td class="text-success">Category saved</td><tr>')
                }
            });
        }
        /*$('form#form_verify_imei').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{route('stock-adjustment.verify')}}',
                dataType: 'json',
                type: 'get',
                data: {
                    imei: $('input[name=imei]').val(),
                },
                success: function (data, textStatus, jQxhr) {
                    console.log(data)
                    if (data == 0) {
                        $('.on_error').addClass('has-error')
                        $('#imei_exist').html("Imei not available in stock!")
                    }
                    else {
                        $('.on_error').removeClass('has-error')
                        $('#imei_exist').html("")
                        $('#verified'+data).html('')
                        $('#imei_success').append('<tr><td class="text-success">'+data+'</td><tr>')
                        $('input[name=imei]').val('')
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            })
        })*/

    </script>
@endpush