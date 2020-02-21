@extends('layouts.customer.app')

@section("title")
    Prodcut Catalog
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
                    <h3 class="m-subheader__title ">Prodcut Catalog</h3>
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
                            <form action="{{URL::to('cataloge/' . 123)}}" method="get" class="form-inline" role="form">
                                <div class="form-group">
                                    <select class="form-control" name="brand_id" id="selected_brand"  onchange="getModelByBrand()">
                                        <option value=""> brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="model" id="selected_model" >
                                        <option value="" selected> models </option>
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
                                    <span class="text-danger">{{\App\Brand::find(request()->input('brand_id'))->name}} </span> >
                                    <span class="text-primary">{{request()->input('model')}}</span> >
                                    <span class=""> {{request()->input('lot_id')}} </span>

                                </div>
                            </div>
                        @endif
                    </div>
                    <hr>
                    @if(count($products) > 0)
                        <div class="row" style="margin: 3%;">
                            <table id="example" class="table table-hover"  style="width:100%">
                                <thead>
                                <tr>
                                    <td style="width: 150px;">Model</td>
                                    <td style="width: 150px;">Network</td>
                                    <td style="width: 150px;">Storage</td>
                                    <td style="width: 150px;">Color</td>
                                    <td style="width: 150px;">Quantity</td>
                                    <td style="width: 150px;">Remaining_Quantity</td>
                                    <td style="width: 150px;">Inventory_Quantity</td>
                                </tr>
                                </thead>
                                <tfoot >
                                <tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                                </tfoot>
                                <tbody>
                                @foreach($products as $value)
                                    <tr>
                                        <td>{{$value->model}}</td>
                                        <td>{{$value->storage->name}}</td>
                                        <td>{{$value->network->name}}</td>
                                        <td>{{$value->color}}</td>
                                        <td>{{$value->asin_total_quantity}}</td>
                                        <td>{{$value->asin_total_quantity - $value->inventory_quantity}}</td>
                                        <td>{{$value->inventory_quantity}}</td>
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
        $('#example').DataTable({
            "displayLength": 100,

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // converting to interger to find total
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // computing column Total of the complete result
                var monTotal = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var tueTotal = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var wedTotal = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var thuTotal = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                var friTotal = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                // Update footer by showing the total with the reference of the column index
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 1 ).footer() ).html(monTotal);
                $( api.column( 2 ).footer() ).html(tueTotal);
                $( api.column( 3 ).footer() ).html(wedTotal);
                $( api.column( 4 ).footer() ).html(thuTotal);
                $( api.column( 5 ).footer() ).html(friTotal);
            },
        })
    </script>
@endpush