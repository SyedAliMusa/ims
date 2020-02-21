@extends('layouts.customer.app')

@section("title")
    Orders | all
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
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Orders</h3>
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
            {{-- <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
                 <div class="m-alert__icon">
                     <i class="flaticon-exclamation m--font-brand"></i>
                 </div>
                 <div class="m-alert__text">
                     DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.
                     For more info see <a href="https://datatables.net/" target="_blank">the official home</a> of the plugin.
                 </div>
             </div>--}}
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand  m-tabs-line--right m-tabs-line-danger" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_buttons_default" role="tab">All</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_buttons_square" role="tab">Pending</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_buttons_square" role="tab">Processing</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_buttons_square" role="tab">Fulfilled</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_buttons_square" role="tab">Partially Fulfilled</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_buttons_square" role="tab">Cancelled</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item"></li>
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                        <i class="la la-ellipsis-h m--font-brand"></i>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__section m-nav__section--first">
                                                            <span class="m-nav__section-text">Quick Actions</span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text">Create Post</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                <span class="m-nav__link-text">Send Messages</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-multimedia-2"></i>
                                                                <span class="m-nav__link-text">Upload File</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__section">
                                                            <span class="m-nav__section-text">Useful Links</span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-info"></i>
                                                                <span class="m-nav__link-text">FAQ</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">Support</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit m--hide">
                                                        </li>
                                                        <li class="m-nav__item m--hide">
                                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Submit</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group">
                        <input type="text"
                               class="form-control input_border" name="query" id="" aria-describedby="helpId" placeholder="Search " autofocus>
                        <small id="helpId" class="form-text text-muted text-danger">Search | Order No | Phone | Email | Customer |</small>
                    </div>
                    <!--begin: Datatable -->
                    <p><b id="replacedWithChecked"></b></p>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                        <thead>
                        <tr>
                            <th id="checkAll" title="select all variants">Order No</th>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Fulfillment status</th>
                            <th>Order Items</th>
                            <th>Order Total</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td></td>
                                <td title="view order"><a href="{{URL::to('orders/' . $order['id'])}}">{{$order['order_number']}}</a></td>
                                <td>{{$order['customer']['first_name']}} {{$order['customer']['last_name']}}</td>
                                <td>{{$order['phone']}}</td>
                                <td>{{$order['email']}}</td>
                                <td>{{$order['shipping_address']['city']}}</td>
                                <td>{{$order['total_price']}}</td>
                                <td>{{count($order['line_items'])}}</td>
                                <td><span class="m-badge  m-badge--primary m-badge--wide">{{$order['fulfillment_status']}}</span></td>
                                <td>{{$order['created_at']}}</td>
                                <td nowrap></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>

    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {
            //select all checkboxes
            var generateBarcode = '<button type="button" class="m-btn btn btn-primary btn-sm">Edit selected orders</button>'
            $("#checkAll").change(function(){  //"select all" change
                var status = $('#checkAll label input[type=checkbox]').is(':checked') // "select all" checked status
                var count = 0
                if (status) {
                    $('#m_table_1 tr td label input[type="checkbox"]').each(function(){ //iterate all listed checkbox items
                        this.checked = status; //change ".checkbox" checked status
                        count = count + 1
                    });
                    if (count > 0){
                        $('#replacedWithChecked').html(count+' order selected '+ generateBarcode)
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
                    $('#replacedWithChecked').html(countCheckedCheckboxes+' order selected '+ generateBarcode)
                }
                else {
                    $('#replacedWithChecked').html('')
                }
            });
        })


    </script>
@endpush