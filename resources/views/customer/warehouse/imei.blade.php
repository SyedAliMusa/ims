@extends('layouts.customer.app')

@section("title")
    IMEI
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .hide{
            display: none !important;
        }
         @if (!session('Account'))
        .account{
            display: none;
        }
        @endif
        
    </style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>

@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row">
                     @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <div class="col-md-12">
                                <form method="post" action="{{route('storeimei')}}" enctype="multipart/form-data" role="form" id="form_verify_imei" class="m--margin-5">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group on_error">
                                                <b class="text-primary">Select IMEI Unlock Code File</b>
                                                <input type="file" title="Verify imei "
                                                       class="form-control input_border" accept=".txt" name="file" autofocus autocomplete="off">
                                            </div>
                                        </div>
                                       
                                       
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <b class="text-white">.</b>
                                                <button type="submit" class="btn btn-primary form-control">Upload</button>

                                            </div>
                                        </div>
                                        <small id="imei_exist" class="text-danger">{{session('fail')}}</small>
                                        <small id="imei_exist" class="text-success">{{session('success')}}</small>
                                        <small id="imei_exist" class="text-primary">{{session('already_verified')}}</small>
                                    </div>

                                </form>
                                <!--begin: Datatable -->
                                <p class="text-info">Unlocak code related to IMEI </p>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        {{--<th>Brand</th>--}}
                                        <th>SR</th>
                                        {{--<th>Network</th>--}}
                                        <th>IMEI</th>
                                        <th>Unlock Code</th>
                                       
                                    </tr>
                                    </thead>
                                    <tbody>
                                  
                                    </tbody>
                                </table>
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
    </div>

@stop
@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

 <script>
    $(document).ready(function () {
        $('.table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ route('getimei') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "id" },
                { "data": "imei" },
                { "data": "code" }
            ]	 

        });
    });
</script>
@endpush