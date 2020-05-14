@extends('layouts.customer.app')

@section("title")
    Stock Out
@endsection
@push("css")
    {{--include intIssued To ernal css--}}
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
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row">
                        {{--release--}}
                        @if (session('issued_to') or request()->input('release'))
                            <div class="col-md-12">
                                <h4 class="text-danger">{{session('deleted')}}</h4>
                                <div class="row" style="margin: 3%;">
                                    <div class="col-md-8">
                                        <form method="post" action="{{route('warehouse.store')}}" role="form" id="form_verify_imei">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <b class="text-primary">Color Folder</b>
                                                        <select class="form-control select_tags" name="color_folder" required>
                                                            <option value="">Select Color Folder</option>
                                                            @if (session('issued_to'))
                                                                <option value="{{session('color_folder')}}" selected>{{session('color_folder')}}</option>
                                                            @endif
                                                            <option value="black">Black</option>
                                                            <option value="purple">Purple</option>
                                                            <option value="blue">Blue</option>
                                                            <option value="green">Green</option>
                                                            <option value="pink">Pink</option>
                                                            <option value="red">Red</option>
                                                            <option value="orange">Orange</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <b class="text-primary">Issued To </b>
                                                        <select class="form-control select_tags" name="issued_to" required>
                                                            <option value="">Select User</option>
                                                            @if (session('issued_to'))
                                                                <option value="{{session('issued_to')}}" selected>{{session('issued_to')}}</option>
                                                            @endif
                                                            <option value="Tester">Tester</option>
                                                            <option value="Refurbisher">Refurbisher</option>
                                                            <option value="Reinel">Reinel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div col-md-3>
                                                    <div class="form-group account">
                                                        <b class="text-primary">Account </b>
                                                        <select class="form-control" name="Account" >
                                                            <option value="">  </option>
                                                           @foreach($user as $x)
                                                           @if (session('Account'))
                                                                <option value="{{session('Account')}}" selected>{{session('Account')}}</option>
                                                            @endif
                                                             @if (session('Account') != $x->name)
                                                           <option value="{{$x->name}}">{{$x->name}}</option>
                                                             @endif

                                                           @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group on_error">
                                                        <b class="text-primary">Issue "IMEI" From Warehouse</b>
                                                        <input type="hidden" name="submit">
                                                        <input type="text" title="Verify imei "
                                                               class="form-control input_border" name="imei" placeholder="Search IMEI" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%"
                                                               @if (session('issued_to'))
                                                               autofocus
                                                               @endif
                                                               autocomplete="off">
                                                        <small id="imei_exist" class="text-danger" style="font-size: x-large">{{session('fail_release')}}</small>
                                                        <small id="imei_exist" class="text-success" style="font-size: x-large">{{session('success_release')}}</small>
                                                        <small id="imei_exist" class="text-primary" style="font-size: x-large">{{session('already_verified')}}</small>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{--<div class="col-md-4">
                                        <form method="get" action="{{url()->current()}}">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <b class="text-primary">Check Issued To Report</b>
                                                        <select class="form-control" name="issued_to_for_report" required>
                                                            <option value="">  </option>
                                                            <option value="Tester">Tester</option>
                                                            <option value="Refurbisher">Refurbisher</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <b class="text-white">Check</b>
                                                    <button type="submit"
                                                            class="btn btn-outline-warning">Get Report
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>--}}
                                </div>
                                <div class="row" style="margin: 3%;">
                                    @if(count($products_release) > 0)

                                        <div class="col-md-12">
                                            <p class="text-info">Total ({{$products_release->total}}) items Stocked Out</p>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    {{--<th>Brand</th>--}}
                                                    <th>Model</th>
                                                    {{--<th>Network</th>--}}
                                                    <th>Color</th>
                                                    <th>Imei</th>
                                                    <th>Category</th>
                                                    {{--<th>Status</th>--}}
                                                    <th>Issued To</th>
                                                     <th>Name</th>
                                                    {{--<th>Added By</th>--}}
                                                    {{--<th>Date</th>--}}
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($products_release as $product)

                                                    <tr>
                                                        {{--                                                    <td>{{$product->inventory->lot->brand->name}}</td>--}}
                                                        {{--                                                    <td>{{$product->inventory->lot->network->name}}</td>--}}
                                                        @if ($product->inventory->lot)
                                                            <td>{{$product->inventory->lot->model}}</td>
                                                            <td>{{$product->inventory->lot->color}}</td>
                                                            @else
                                                            <td></td>
                                                            <td></td>
                                                        @endif

                                                        <td>{{$product->inventory->imei}}</td>
                                                        <td>{{$product->inventory->category->name}}</td>
                                                        {{--<td>
                                                            <span class="m-badge  m-badge--info m-badge--wide">In Progress</span>
                                                        </td>--}}
                                                        <td>{{$product->issued_to}}</td>
                                                         <td>{{$product->Account}}</td>
                                                        {{--                                                    <td>{{$product->user->name}}</td>--}}
                                                        {{--                                                    <td title="{{$product->created_at}}">{{date('M-d-Y', strtotime($product->created_at))}}</td>--}}
                                                        <td>
                                                            <form action="{{URL::to('warehouse/' . $product->id)}}" method="post">
                                                                {{csrf_field()}}
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit"  onclick="return confirm('Are you sure?')"  class="btn btn-outline-danger btn-sm">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            @if ($products_release->total > 19)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="btn-group" role="group" aria-label="First group">
                                                            @if ($products_release->previousPageUrl())
                                                                <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products_release->previousPageUrl()}}">Previous</a>
                                                            @endif
                                                            @if ($products_release->nextPageUrl())
                                                                <a  class="m-btn btn btn-outline-brand btn-sm" href="{{$products_release->nextPageUrl()}}">Next</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{--recived--}}
                            <div class="col-md-12">
                                <form method="post" action="{{route('warehouse.destroy',['id'=>'out'])}}" role="form" id="form_verify_imei" class="m--margin-5">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group on_error">
                                                <b class="text-primary">Push "IMEI" Back In Warehouse</b>
                                                <input type="hidden" name="submit">
                                                {{csrf_field()}}
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="text" title="Verify imei "
                                                       class="form-control input_border" name="imei" placeholder="Search IMEI" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" width="25%" autofocus autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <b class="text-white">.</b>
                                                <select class="form-control" name="category_id" required>
                                                    <option value="" selected></option>
                                                    @foreach(\App\Category::all() as $category)

                                                        <option value="{{$category->id}}" @if(session('id') && session('id') == $category->id ) selected  @endif >{{$category->name}}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <b class="text-white">.</b>
                                                <button type="submit" class="btn btn-primary form-control">Save</button>

                                            </div>
                                        </div>
                                        <small id="imei_exist" class="text-danger" style="font-size: x-large">{{session('fail')}}</small>
                                        <small id="imei_exist" class="text-success" style="font-size: x-large">{{session('success')}}</small>
                                        <small id="imei_exist" class="text-primary" style="font-size: x-large">{{session('already_verified')}}</small>
                                    </div>

                                </form>
                                <!--begin: Datatable -->
                                <p class="text-info">Last item received to warehouse is below </p>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        {{--<th>Brand</th>--}}
                                        <th>Model</th>
                                        {{--<th>Network</th>--}}
                                        <th>Color</th>
                                        <th>Imei</th>
                                        <th>Category</th>
                                        {{--<th>Status</th>--}}
                                        <th>Issued To</th>
                                        {{--<th>Added By</th>--}}
                                        {{--<th>Date</th>--}}
                                        {{--<th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products_received as $product)
                                        <tr>
                                            {{--                                        <td>{{$product->inventory->lot->brand->name}}</td>--}}
                                            <td>{{$product->inventory->lot->model}}</td>
                                            {{--                                        <td>{{$product->inventory->lot->network->name}}</td>--}}
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
                                            {{--                                        <td>{{$product->user->name}}</td>--}}
                                            {{--                                        <td title="{{$product->created_at}}">{{date('M-d-Y', strtotime($product->created_at))}}</td>--}}
                                            {{--<td>
                                                <button type="button" onclick="update_networks({{$product->inventory->id}})" class="btn
                                                btn-outline-warning">Edit</button>
                                            </td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
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
    <script>
           jQuery(document).ready(function(){
                $('.select_tags').on('change', function() {
                var x =  $(this).find(":selected").val();
                if(x == 'Refurbisher'){
                    $('.account').show();
                }else{
                    $('.account').hide();
                }
            });
           })
    </script>
@endpush
