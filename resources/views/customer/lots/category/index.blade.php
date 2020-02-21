@extends('layouts.customer.app')

@section("title")
    Category
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
                    <h3 class="m-subheader__title ">Category</h3>
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
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <form action="{{route('category.store')}}" method="post" class="form-inline" role="form">
                                        {{csrf_field()}}
                                        <span>Create Category</span>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="network"  value="" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary"> Save </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <p><b id="replacedWithChecked"></b></p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $network)
                            <tr>
                                <td>{{$network->id}}</td>
                                <td>{{$network->name}}
                                    <form action="{{URL::to('category/' . $network->id)}}" method="post"
                                          role="form" class="hide form-inline" id="network{{$network->id}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="text"    class="form-control" name="update_network"value="{{$network->name}}" autofocus="autofocus">
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-primary">Save</button>
                                    </form>
                                </td>
                                <td title="{{$network->created_at}}">{{ date('M-d-Y', strtotime($network->created_at))}}</td>
                                <td>{{$network->user->name}}</td>
                                <td>
                                    <button type="button" onclick="update_networks({{$network->id}})" class="btn
                                    btn-success">Edit</button>
                                </td>
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
    <script>
        function update_networks(id){
            $('#network'+id).removeClass('hide')
        }


    </script>
@endpush