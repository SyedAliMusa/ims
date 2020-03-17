@extends('layouts.customer.app')

@section("title")
    Network
@endsection
@push("css")
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
                    <h3 class="m-subheader__title ">Models</h3>
                </div>
                <div>
                    <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                        <span class="m-subheader__daterange-label">
                            <h3 style="color: darkred;">{{session('error')}}</h3>
                        </span>
                    </span>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">Create Model</h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <form action="{{route('models.store')}}" method="post" class="form-inline" role="form">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label><strong>Brand</strong> &nbsp;&nbsp;</label>
                                <select class="form-control" name="brand" id="m_select2_1" required>
                                    <option value="" selected></option>
                                    @foreach($brands as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                {{--<input type="text" class="form-control" name="brand_id"  value="" required>--}}
                            </div>
                            <div class="form-group">
                                <label>&nbsp;&nbsp;<strong>Model</strong>&nbsp;&nbsp;</label>
                                <input type="text" class="form-control" name="model"  value="" required>&nbsp;&nbsp;
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <p><b id="replacedWithChecked"></b></p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Brand ID</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $network)
                            <tr>
                                <td>{{$network->id}}</td>
                                <td>{{$network->bname}}</td>
                                <td>{{$network->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script src="{{asset('customer/assets/demo/default/custom/crud/forms/widgets/select2.js')}}" type="text/javascript"></script>
    <script>
        function update_networks(id){
            $('#network'+id).removeClass('hide')
        }


    </script>
@endpush