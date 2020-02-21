@extends('layouts.customer.app')

@section("title")
    Lot | Asin
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
                    <h3 class="m-subheader__title ">Lot > Asin</h3>
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
                </div>
                <div class="m-portlet__body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>LotID</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Storage</th>
                            <th>Asin</th>
                            <th>Asin Quantity</th>
                            <th>Inventory Quantity</th>
                            <th>Remaining Quantity</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->lot_id}}</td>
                                <td>{{$product->brand->name}}</td>
                                <td>{{$product->model}}</td>
                                <td>{{$product->network->name}}</td>
                                <td>{{$product->color}}</td>
                                <td>{{$product->storage->name}}</td>
                                <td>{{$product->asin}}</td>
                                <td>{{$product->asin_total_quantity}}</td>
                                <td>{{$product->inventory_quantity}}</td>
                                <td><?php echo $product->asin_total_quantity - $product->inventory_quantity ?></td>
                                <td>{{$product->user->name}}</td>
                                <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                <td>

                                    <div class="btn-group" role="group" aria-label="First group">
                                        <a class="btn btn-outline-warning btn-sm" href="{{route('lots.edit', $product->id)}}">
                                            Edit
                                        </a>
                                        <form action="{{URL::to('lots/' . $product->id)}}" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit"  onclick="return confirm('Are you sure?')"  class="btn btn-outline-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                </td>

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

    </script>
@endpush