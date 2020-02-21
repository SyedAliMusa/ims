@extends('layouts.customer.app')

@section("title")
    Stock Out
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .hide{
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Phones from Testers </h3>
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    @if (auth()->user()->account_type != 'admin') <h5>Phones from Testers  of (<b> {{auth()->user()->name}} </b>)</h5>@endif
                                    <br>
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                    {{--<th>Brand</th>--}}
                                                    <th>Model</th>
                                                    {{--<th>Network</th>--}}
                                                    <th>Color</th>
                                                    <th>Imei</th>
                                                    <th>Category</th>
                                                    {{--<th>Status</th>--}}
                                                    <th>Issued To</th>
                                                     <th>Name</th>
                                                    {{--<th>Added By</th>--}}
                                                    {{--<th>Date</th>--}}
                                                   
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                @foreach($products_release as $product)
                                                @if($product->Account == auth()->user()->name && $product->acc_status != '1' && $product->acc_status == "2" && $product->issued_to == 'Refurbisher')
                                                
                                                    <tr>
                                                        {{--                                                    <td>{{$product->inventory->lot->brand->name}}</td>--}}
                                                        {{--                                                    <td>{{$product->inventory->lot->network->name}}</td>--}}
                                                        @if ($product->inventory->lot)
                                                            <td>{{$product->inventory->lot->model}}</td>
                                                            <td>{{$product->inventory->lot->color}}</td>
                                                            @else
                                                            <td></td>
                                                            <td></td>
                                                        @endif

                                                        <td>{{$product->inventory->imei}}</td>
                                                        <td>{{$product->inventory->category->name}}</td>
                                                        {{--<td>
                                                            <span class="m-badge  m-badge--info m-badge--wide">In Progress</span>
                                                        </td>--}}
                                                        <td>{{$product->issued_to}}</td>
                                                        <td>{{$product->Account}}</td>
                                             </tr>
                                             @endif
                                                @endforeach
                                                </tbody>
                                            
                                            
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@push('scripts')
    <script>

    </script>
@endpush