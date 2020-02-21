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
                    <h3 class="m-subheader__title ">LCD History</h3>
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
                                    @if (auth()->user()->account_type != 'admin') <h5>LCD History of (<b> {{auth()->user()->name}} </b>)</h5>@endif
                                    <br>
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Category</th>
                                            <th>Color</th>
                                            <th>Barcode</th>
                                            @if (auth()->user()->account_type == 'admin')
                                                <th>Assign To</th>
                                                <th>Name</th>
                                            @endif
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (auth()->user()->account_type == 'admin')
                                            @foreach(\App\LcdIssuedTo::orderByDesc('id')->get() as $product)
                                            
                                                <tr>
                                                    <td>{{$product->lcd_inventory->brand->name}}</td>
                                                    <td>{{$product->lcd_inventory->modal}}</td>
                                                    <td>{{$product->lcd_inventory->category->name}}</td>
                                                    <td>{{$product->lcd_inventory->color}}</td>
                                                    <td>{{$product->lcd_inventory->barcode}}</td>
                                                    <td>{{$product->assigned_to}}</td>
                                                    @if ($product->assigned_to == 'LCD_Refurbished')
                                                        @if ($product->user)
                                                            <td>{{$product->user->name}}</td>
                                                        @else
                                                            <!--<td>{{$product->assigned_to}}</td>-->
                                                            <td>Rainel</td>
                                                        @endif
                                                    @else
                                                        @if ($product->user)
                                                            <td>{{$product->user->name}}</td>
                                                        @else
                                                            <td>{{$product->assigned_to}}</td>
                                                        @endif
                                                    @endif
                                                    <td>{{config('general.lcd_inventory_status.'.$product->status)}}</td>
                                                    <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->updated_at))}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <?php
                                           $imei_ids = \App\AttachIMEIToLCD::pluck('lcd_inventory_id')->toArray();
                                          $datas =  \App\LcdIssuedTo::orderBy('status')
                                                ->where('assigned_to_account','=', auth()->id())
                                                ->where('status','=', 3)
                                                ->whereNotIn('lcd_inventory_id', $imei_ids)
                                                ->get()


                                            ?>
                                            @foreach($datas as $product)
                                        
                                            @if($product->lcd_inventory->status != '5')
                                                <tr>
                                                    <td>{{$product->lcd_inventory->brand->name}}</td>
                                                    <td>{{$product->lcd_inventory->modal}}</td>
                                                    <td>{{$product->lcd_inventory->category->name}}</td>
                                                    <td>{{$product->lcd_inventory->color}}</td>
                                                    <td>{{$product->lcd_inventory->barcode}}</td>
                                                    <td>{{config('general.lcd_inventory_status.'.$product->status)}}</td>
                                                    <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        @endif
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