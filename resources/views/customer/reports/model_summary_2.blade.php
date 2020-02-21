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
        .border_t_b{
            border-top: 2px solid gainsboro !important;
            border-bottom: 2px solid gainsboro !important;
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
                                    <select class="form-control" name="model" id="selected_model" required >
                                        <option value="" selected> model</option>
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
                <?php $total_stock = 0 ?>
                <?php $total_available = 0 ?>
                <!--begin: Datatable -->
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th>Color</th>
                            <th>storage</th>
                            <th>Category</th>
                            <th>Sold</th>
                            <th>Available</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        @foreach($products as $product)
                            <?php $show_color = true?>
                            <tbody style="border-bottom: 3px solid;">
                            @foreach($product->storages as $storage)
                                <?php $show_storage = true?>
                                <?php $total_stock_for_storage = 0?>
                                <?php $total_available_for_storage = 0?>
                                @foreach($storage->categories as $category)
                                    <tr >
                                        @if ($show_color)
                                            <td class="text-primary">{{$product->color}}</td>
                                            <?php $show_color = false?>
                                        @else
                                            <td>&nbsp; &nbsp;</td>
                                        @endif
                                        @if ($show_storage)
                                            <td class="bg-secondary">{{\App\Storages::find($storage->storage_id)->name}}</td>
                                            <?php $show_storage = false?>
                                        @else
                                            <td>&nbsp; &nbsp;</td>
                                        @endif
                                        <td>{{\App\Category::find($category->category_id)->name}}</td>
                                        <td class="text-danger"><?php echo $category->stock - $category->available?></td>
                                        <td class="text-success">{{$category->available}}</td>
                                        <td class="text-primary">{{$category->stock}}</td>
                                        <?php $total_stock = $total_stock + $category->stock ?>
                                        <?php $total_stock_for_storage = $total_stock_for_storage + $category->stock?>
                                        <?php $total_available = $total_available + $category->available ?>
                                        <?php $total_available_for_storage = $total_available_for_storage + $category->available?>
                                    </tr>
                                @endforeach
                                <?php $show_storage = true?>
                                <tr class="font-weight-bold" >
                                    <td></td>
                                    <td></td>
                                    <td class="border_t_b">Total</td>
                                    <td class="border_t_b">{{$total_stock_for_storage - $total_available_for_storage}}</td>
                                    <td class="border_t_b">{{$total_available_for_storage}}</td>
                                    <td class="border_t_b">{{$total_stock_for_storage}}</td>
                                </tr>
                                <tr class="text-white">
                                    <td colspan="10">.</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <?php $show_color = true?>
                        @endforeach
                    </table>

                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <h3 class="text-center">Grand Total</h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sold</th>
                                    <th>Available</th>
                                    <th>Bought</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-danger">{{$total_stock - $total_available}}</td>
                                    <td class="text-success">{{$total_available}}</td>
                                    <td class="text-primary">{{$total_stock}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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

    </script>
@endpush