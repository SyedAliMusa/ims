@extends('layouts.customer.app')

@section("title")
    Users
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
                    <h3 class="m-subheader__title ">Users</h3>
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
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Password</th>
                            <th>Account Type</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>{{$product->name}}</td>
{{--                                <td>{{$product->email}}</td>--}}
                                <td>***********
                                    <form action="{{URL::to('user/' . $product->id)}}" method="post"
                                          role="form" class="hide form-inline" id="network{{$product->id}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="text"    class="form-control" name="password"value="" autofocus="autofocus" required>
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-primary">update</button>
                                    </form>
                                </td>
                                <td>{{$product->account_type}}</td>
                                <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                <td>
                                    <button type="button" onclick="update_networks({{$product->id}})" class="btn btn-sm  btn-success">Change Password</button>
                                    <a type="button" href="{{route('user.show',$product->id)}}" class="btn btn-primary btn-sm">Set Privileges</a>
                                    <a onclick="return confirm('Are you sure?')"  class="btn btn-outline-danger btn-sm" href="{{URL::to('user/' . $product->id)}}">
                                        Delete
                                    </a>
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