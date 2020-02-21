@extends('layouts.customer.app')

@section("title")
    Stock In
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .input_border{
            border-width: 2px;
        }
        .hide{
            display: none; !important;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Receive stock to warehouse</h3>
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
                    <div class="row" style="margin: 3%;">
                        <div class="col-md-4 offset-4">
                            <form method="post" action="{{route('warehouse.destroy',['id'=>'out'])}}" role="form" id="form_verify_imei">
                                {{csrf_field()}}
                                <div class="form-group on_error">
                                    <b class="text-primary">Push "IMEI" Back In Warehouse</b>

                                    <input type="hidden" name="submit">
                                    {{csrf_field()}}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="text" title="Verify imei "
                                           class="form-control input_border" name="imei" placeholder="Search IMEI" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%" autofocus autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <select class="form-control" name="category_id" required>
                                                <option value="" selected></option>
                                                @foreach(\App\Category::all() as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <small id="imei_exist" class="text-danger">{{session('fail')}}</small>
                                    <small id="imei_exist" class="text-success">{{session('success')}}</small>
                                    <small id="imei_exist" class="text-primary">{{session('already_verified')}}</small>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!--begin: Datatable -->
                    <p class="text-info">Last item received to warehouse is below </p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Issued To</th>
                            <th>Added By</th>
                            <th>Date</th>
                            {{--<th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->inventory->lot->brand->name}}</td>
                                <td>{{$product->inventory->lot->model}}</td>
                                <td>{{$product->inventory->lot->network->name}}</td>
                                <td>{{$product->inventory->lot->color}}</td>
                                <td>{{$product->inventory->imei}}</td>
                                <td>{{$product->inventory->category->name}}

                                    <form action="{{URL::to('warehouse/' . $product->inventory->id)}}" method="post"
                                          role="form" class="hide form-inline" id="network{{$product->inventory->id}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select class="form-control" name="category_id">
                                                    @foreach(\App\Category::all() as $category)
                                                        @if ($category->name == $product->inventory->category->name)
                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                        @else
                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>

                                    </form>

                                </td>
                                <td>
                                    <span class="m-badge  m-badge--info m-badge--wide">In Progress</span>
                                </td>
                                <td>{{$product->issued_to}}</td>
                                <td>{{$product->user->name}}</td>
                                <td title="{{$product->created_at}}">{{date('M-d-Y', strtotime($product->created_at))}}</td>
                                {{--<td>
                                    <button type="button" onclick="update_networks({{$product->inventory->id}})" class="btn
                                    btn-outline-warning">Edit</button>
                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- @if ($products->total > 19)
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="btn-group" role="group" aria-label="First group">
                                     @if ($products->previousPageUrl())
                                         <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->previousPageUrl()}}">Previous</a>
                                     @endif
                                     @if ($products->nextPageUrl())
                                         <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products->nextPageUrl()}}">Next</a>
                                     @endif
                                 </div>
                             </div>
                         </div>
                     @endif--}}
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