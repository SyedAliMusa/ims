@extends('layouts.customer.app')

@section("title")
    Set Privileges
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
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <h3>Set Privileges To <b>{{$user->name}}</b></h3>
                    <div class="alert alert-success" role="alert">
                        <strong>{{session('permission_approved')}}</strong>
                    </div>
                    <form action="{{url()->current()}}" method="get" class="form-horizontal" role="form">
                        <input type="hidden" name="set_permission" value="true">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Privileges</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Permissions::all() as $product)
                                <tr>
                                    <td>{{$product->display_name}}</td>
                                    <?php $has_permission = \App\UserPermissions::where('u_id','=', $user->id)->where('p_id','=', $product->id)->first() ?>
                                    @if ($has_permission)
                                        <td>
                                            <div class="text-center">
                                                <input type="checkbox" class="form-check-input" checked name="Privileges[]"
                                                       value="{{$product->id}}">
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            <div class="text-center">
                                                <input type="checkbox" class="form-check-input" name="Privileges[]"
                                                       value="{{$product->id}}">
                                            </div>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-brand">Save Privileges</button>
                    </form>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation m--font-brand"></i>
            </div>
            <div class="m-alert__text text-white">
                DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.
                For more info see
            </div>
        </div>
    </div>
@stop
@push('scripts')
@endpush