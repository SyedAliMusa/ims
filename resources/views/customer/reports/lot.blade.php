@extends('layouts.customer.app')

@section("title")
    Report | Lot
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
                    <h3 class="m-subheader__title ">Report | Lot</h3>
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
                        <div class="col-md-4">
                            <form action="{{url()->current()}}" method="get" class="form-inline" role="form">
                                <button type="submit" class="btn btn-warning">Reset all</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{url()->current()}}" method="post" class="form-inline" role="form">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" required onchange="getLotByBrandPlusModel()">
                                        <option value="" selected> models </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="lot_id" id="selected_lot" required>
                                        <option value="" selected> lots </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="    width: 100px !important"> filter </button>
                                </div>
                            </form>
                        </div>
                        @if (request()->input('brand_id') and request()->input('model') and request()->input('lot_id'))
                            <div class="row">
                                <div class="col-md-5 col-offset-1" style="    margin-left: 2%;">
                                    {{--                                    <span class="text-danger">{{\App\Models\Brand::find(request()->input('brand_id'))->name}} </span> >--}}
                                    <span class="text-primary">{{request()->input('model')}}</span> >
                                    <span class=""> {{request()->input('lot_id')}} </span>

                                </div>
                            </div>
                        @endif
                    </div>
                    <hr>
                    @if(count($products) > 0)
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="myChart" style="max-width: 500px;"></canvas>
                            <?php $total_stock = 0 ?>
                            <?php $total_available = 0 ?>
                            <!--begin: Datatable -->
                                <table class="table table-bordered table-hover table-sm" style="margin-top: 5%">
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
                                    @foreach($color_summary as $product)
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
                            <div class="col-md-4">
                                <h6>Lot Information</h6>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>Brand</td>
                                        <td>{{$products[0]->brand->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Model</td>
                                        <td>{{$products[0]->model}}</td>
                                    </tr>
                                    <tr>
                                        <td>Network</td>
                                        <td>{{$products[0]->network->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Storage Option(s)</td>
                                        <td>
                                            @foreach($storages as $item)
                                                {{$item->storage->name}},
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Color(s)</td>
                                        <td>
                                            @foreach($colors as $item)
                                                {{$item->color}},
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr style="background-color: blue; color: white">
                                        <td>Original Quantity in asin</td>
                                        <td>{{$total_asin_quantity}}</td>
                                    </tr>
                                    <tr style="background-color: orangered; color: white">
                                        <td>Quantity Dispatched</td>
                                        <td>{{$dispatched_quantity}}</td>
                                    </tr>
                                    <tr style="background-color: green; color: white">
                                        <td>Quantity available</td>
                                        <td>{{$available_quantity}}</td>
                                    </tr>
                                    <tr style="background-color: #ff9900; color: white">
                                        <td>Quantity remaining</td>
                                        <td>{{$total_asin_quantity - ($available_quantity + $dispatched_quantity)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantity Bought</td>
                                        <td>{{$products[0]->bought_quantity}}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantity Received</td>
                                        <td>{{$products[0]->received_quantity}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->lot_id}}</td>
                                        <td>{{$product->asin}}</td>
                                        <td>{{$product->brand->name}}</td>
                                        <td>{{$product->model}}</td>
                                        <td>{{$product->network->name}}</td>
                                        <td>{{$product->color}}</td>
                                        <td>{{$product->imei}}</td>
                                        <td>{{\App\Category::find($product->category_id)->name}}</td>
                                        <td>
                                            @if ($product->status == 1)
                                                <span class="m-badge  m-badge--success m-badge--wide"> &nbsp; Available &nbsp;</span>
                                            @else
                                                <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>
                                            @endif
                                        </td>
                                        <td>{{\App\User::find($product->created_by)->name}}</td>
                                        <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Original","Dispatched", "Available"],
                datasets: [{
                    label: '# Original Quantity',
                    data: [{{$total_asin_quantity}}, {{$dispatched_quantity}},{{$available_quantity}}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        /*  'rgba(75, 192, 192, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(255, 159, 64, 0.2)'*/
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(255, 206, 86, 1)',
                        /*'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'*/
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
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


        // get lots by brands+modal
        function getLotByBrandPlusModel() {
            var brand_id =  $('select[name=brand_id]').val();
            var model =  $('select[name=model]').val();
            $.ajax({
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "model": model,
                },
                url: "{{route('lot_by_brand_plus_model')}}",
                success: function (data) {
                    $('select[name=lot_id]').html('<option value="" selected> lots </option>')
                    $.each(data, function (index, value) {
                        $('select[name=lot_id]').append('<option value=' + value.lot_id + '>' + value.lot_id + '</option>')
                    });
                }
            });
        }

    </script>
@endpush