@extends('layouts.customer.app')

@section("title")
    Products
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
                    <h3 class="m-subheader__title ">Incomings</h3>
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
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">All

                            </h3>
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
                        <small id="helpId" class="form-text text-muted text-danger">Search | title | SKU | Barcode | Vendor | Product type |</small>
                    </div>
                    <!--begin: Datatable -->
                    <p><b id="replacedWithChecked"></b></p>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                        <thead>
                        <tr>
                            <th id="checkAll" title="select all variants">Record ID</th>
                            <th>Product variant</th>
                            <th>Sku</th>
                            <th>Barcode</th>
                            <th>Existing_Qty</th>
                            <th>Incoming_Qty</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Date added</th>
                            <th>AddedBy</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr role="row" class="odd">
                            <td class=" dt-right" tabindex="0">
                                <label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">
                                    <input type="checkbox" value="" class="m-checkable">
                                    <span></span>
                                </label></td>
                            <td class="sorting_1">Gleason, Kub and Marquardt 75862-001 Rosenbaum-Reichel</td>
                            <td>Indonesia</td>
                            <td>Pineleng</td>
                            <td>Pineleng</td>
                            <td>Pineleng</td>
                            <td>4 Messerschmidt Point</td>
                            <td>Cherish Peplay</td>
                            <td>McCullough-Gibson</td>
                            <td>11/23/2017</td>
                            <td nowrap="">
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="#"><i class="la la-leaf"></i> Update Status</a>
                                <a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>
                            </div>
                        </span>
                                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                                    <i class="la la-edit"></i>
                                </a></td>
                        </tr>

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
        })


    </script>
@endpush