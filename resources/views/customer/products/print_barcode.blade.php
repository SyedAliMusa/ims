@extends('layouts.customer.app')

@section("title")
    Print Barcode preview
@endsection
@push("css")
    {{--include internal css--}}
    <style>

    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><svg id="barcode1"/></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.8.0/dist/JsBarcode.all.min.js"></script>
    <script>
        $(document).ready(function () {
            JsBarcode("#barcode1", "12345648", {
                format: "CODE128",
                width: 2,
                height: 30,
            });
            JsBarcode("#barcode2", "12sd1211sd", {
                format: "CODE128",
                width: 2,
                height: 30,
            });
        })

    </script>
@endpush