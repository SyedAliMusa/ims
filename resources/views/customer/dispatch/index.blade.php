@extends('layouts.customer.app')

@section("title")
    Dispatch
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Dispatch</h3>
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
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="{{route('dispatch.create')}}" class="btn btn-accent m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
												<span>
													<i class="la la-plus"></i>
													<span>New Dispatch</span>
												</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="col-md-3 offset-9">
                        <form action="{{url()->current()}}" method="get" role="form">
                            <div class="form-group">
                                <input type="hidden" name="submit">
                                <input type="text" title="Search | Lot | ASIN | Brand | Model "
                                       class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search tracking" width="25%" autofocus>
                                {{--<small id="helpId" class="form-text text-muted text-danger">Search | Lot | ASIN | Brand | Model |</small>--}}
                            </div>
                        </form>
                    </div>
                    <!--begin: Datatable -->
                    <p class="text-info">Total ({{$products->total}}) Dispatched</p>
                    <p class="text-danger">{{session('deleted')}}</p>
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Tracking No</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Dispatch Date</th>
                            <th>Action</th>
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
                                <td>{{$product->inventory->category->name}}</td>
                                <td>{{$product->tracking}}</td>
                                <td>
                                    @if ($product->status == 1)
                                        <span class="m-badge  m-badge--warning m-badge--wide">Returned</span>

                                    @else
                                        <span class="m-badge  m-badge--danger m-badge--wide">Dispatched</span>

                                    @endif
                                </td>
                                <td>{{$product->user->name}}</td>
                                <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>
                                <td>
                                    <form action="{{URL::to('dispatch/' . $product->id)}}" method="post">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="First group">
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
@stop
@push('scripts')
    <script>
        /*  $(document).ready(function () {
              //select all checkboxes
              var generateBarcode = '<button type="button" class="m-btn btn btn-primary btn-sm">Generate Barcode</button>'
              $("#checkAll").change(function(){  //"select all" change
                  var status = $('#checkAll label input[type=checkbox]').is(':checked') // "select all" checked status
                  var count = 0
                  if (status) {
                      $('#m_table_1 tr td label input[type="checkbox"]').each(function(){ //iterate all listed checkbox items
                          this.checked = status; //change ".checkbox" checked status
                          count = count + 1
                      });
                      if (count > 0){
                          $('#replacedWithChecked').html(count+' variants selected '+ generateBarcode)
                      }
                      else {
                          $('#replacedWithChecked').html('')
                      }
                  }
                  else {
                      $('#replacedWithChecked').html('')
                  }

              });

              var checkboxes = $('#m_table_1 tr td label input[type="checkbox"]');
              checkboxes.change(function(){
                  var countCheckedCheckboxes = checkboxes.filter(':checked').length;
                  if (countCheckedCheckboxes > 0){
                      $('#replacedWithChecked').html(countCheckedCheckboxes+' variants selected '+ generateBarcode)
                  }
                  else {
                      $('#replacedWithChecked').html('')
                  }
              });
          })*/


    </script>
@endpush