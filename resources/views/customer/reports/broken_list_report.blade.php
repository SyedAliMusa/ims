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
                    <h3 class="m-subheader__title ">Broken LCD Report</h3>
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
                                    <th>Brand</th>
                                    <th>modal</th>
                                    <th>Category</th>
                                    <th>LCD</th>
                                    <th>Broken_by</th>
                                    <th>Created_by</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->brand->name}}</td>
                                        <td>{{$item->modal}}</td>
                                        <td>{{$item->category->name}}</td>
                                        <td>{{$item->barcode}}</td>
                                        @if ($item->user)
                                        <td>{{$item->user->assigned_to_account}}</td>
@else
                                            <td></td>
                                        @endif
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