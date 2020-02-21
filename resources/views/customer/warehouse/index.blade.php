@extends('layouts.customer.app')

@section("title")
    Warehouse
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Warehouse</h3>
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
                    {{--<div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="{{route('warehouse.create')}}" class="btn btn-brand m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
												<span>
													<i class="la la-minus"></i>
													<span>Release stock from warehouse</span>
												</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="{{route('warehouse.show',['id'=>'show'])}}" class="btn btn-success m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
												<span>&nbsp;
													<i class="la la-plus"></i>
													<span>Receive stock to warehouse</span> &nbsp;
												</span>
                                </a>
                            </li>
                        </ul>
                    </div>--}}
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <p class="text-info">Total (<b class="text-danger">{{$products->total}}</b>) items in Warehouse</p>
                    <p class="text-danger">{{session('deleted')}}</p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Asin</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Storage</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
{{--                            @if (!$product->inventory_id)--}}
                            <tr>
                                <td>{{$product->lot->asin}}</td>
                                <td>{{$product->lot->brand->name}}</td>
                                <td>{{$product->lot->model}}</td>
                                <td>{{$product->lot->network->name}}</td>
                                <td>{{$product->lot->storage->name}}</td>
                                <td>{{$product->lot->color}}</td>
                                <td>{{$product->imei}}</td>
                                <td>{{$product->category->name}}</td>
                            </tr>
                            {{--@endif--}}

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