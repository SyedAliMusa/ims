@extends('layouts.customer.app')

@section("title")
    Attach IMEI With LCD
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
                    <h3 class="m-subheader__title ">Attach IMEI With LCD Report</h3>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row" style="margin: 3%;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>IMEI</th>
                                    <th>LCD</th>
                                    <th>Created_by</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->inventory->imei}}</td>
                                        <td>{{$item->lcdInventory->barcode}}</td>
                                        <td>{{$item->user->name}}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
@endpush