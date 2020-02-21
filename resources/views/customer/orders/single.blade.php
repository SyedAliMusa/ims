@extends('layouts.customer.app')

@section("title")
    Orders ~ #1006
@endsection
@push("css")
    {{--include internal css--}}
    <style>

        .border_none{
            border: 1px solid white !important;
        }
        .bg_color{
            background-color: #f7f7f9;
        }
        .row_p_1per{
            padding: 1%;
        }
        .blog{
            padding: 2%;
        }

        /* BASIC CONTACT FORM CSS */
        #contact_form input,
        #contact_form textarea {
            height: 134px;
            line-height: 1.4em;
            padding-left: 20px; /* Change this to fit your label text width */
            width: 100%;
            background-color: #f7f7f9;
        }
        #contact_form textarea {
            padding: 10px;
            border: none;
            border-radius: 3px;
        }
        #contact_form label {
            display: inline-block;
            height: 45px;
            line-height: 1.4em;
            margin-bottom: 20px;
            position: relative;
            width: 100%;
        }
        #contact_form label input {
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        #contact_form label span {
            background-color: #fff;
            font-size: 14px;
            font-weight: 200;
            position: absolute;
            left: 10px;
            top: -10px;
            padding: 0 8px;
            pointer-events: none;
        }
        #contact_form input:focus + span,
        #contact_form input:active + span,
        #contact_form textarea:focus + span,
        #contact_form textarea:active + span {
            top: -9px; /* Change this to fit your label text width */
        }
        #contact_form input[type="submit"] {
            clear: both;
            display: block;
            height: auto;
            padding: 0;
            width: auto;
        }
        /* ANIMATION */
        #contact_form label input {
            transition: padding 0.3s ease 0s;
        }
        #contact_form label span {
            transition: top 0.3s ease 0s, color 0.3s ease 0s;
        }
        #contact_form input:focus,
        #contact_form textarea:focus {
            transition: all 0.3s ease;
            height: 200px;
            box-shadow: 0px -6px 20px #6f98bb !important;
        }
        .hide{
            display: none !important;
        }
        .title_color{
            color: #637381 !important;
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
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand  m-tabs-line--right m-tabs-line-danger" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_buttons_default" role="tab">#1006</a>
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
                                                                <span class="m-nav__link-text">Send email</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">Edit order</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">Merge order</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">Add more product</span>
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
                        <div class="row">
                            <div class="col-md-4 bg_color blog" style="    height: 134px;">
                                <div style="    width: 250px;float: left;">
                                    <b><a href="">work 123</a></b></div>
                                <div style="width: 115px;float: left;"><span style="color: #637381;">has 3 Order(s)</span></div>
                                <p style="color: #637381;">Phone: <b>+92 356475889</b></p>
                                <p style="color: #637381;">Email: <b>email@gmail.com</b></p>
                            </div>
                            <div class="col-sm-4 col-md-4 bg_color blog" style="border-left: 20px solid white;border-right: 20px solid white;height: 134px;color: #637381;">
                                <span>City: <b>lahore</b></span>
                                <div>Shipping Address: </div> <span style="    font-size: 14px;">lahore lahore lahore lahore lahore </span>
                            </div>
                            <div class="col-md-4 bg_color " style="height: 97px;border-radius: 3px">
                                <div id="contact_form" style="margin: 0px -15px">
                                    <label class="font-style">
                            <textarea type="text" id="admin_comments" onclick="edit_admin_comments(123)">

                            </textarea>
                                        <span>Admin comments:</span>
                                        <button type="button" class="btn btn-primary pull-right hide" id="update_admin_comment123"
                                                onclick="adminComments(123)">save</button>

                                    </label>
                                </div>
                            </div>
                        </div>
                        {{--products--}}
                        <div class="row" style="    margin: 1% -1%;">
                            <table class="table table-bordered "width="100%">
                                <thead style="    font-size: 12px;">
                                <tr>
                                    <th>
                                        <input type="checkbox" name="check_all" id="checkAll" >
                                    </th>
                                    <th style="width: 29%">Product Title</th>
                                    <th>SKU</th>
                                    <th style="    width: 10%;">Vendor</th>
                                    <th>Fulfillment status</th>
                                    <th>Price</th>
                                    <th class="text-sm-center">Original Qty</th>
                                    <th>shipped Qty</th>
                                    <th>Total</th>
                                    <th>Inventory Qty</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="title_color" style="    font-size: 13px;">
                                <tr role="row" class="odd">
                                    <td class=" dt-right" tabindex="0">
                                        <label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">
                                            <input type="checkbox" value="" class="m-checkable">
                                            <span></span>
                                        </label></td>
                                    <td class="sorting_1">Gleason, Kub and Marquardt 75862-001 Rosenbaum-Reichel</td>
                                    <td>12312as2d23</td>
                                    <td>Pineleng</td>
                                    <td>Pending</td>
                                    <td>500</td>
                                    <td>4</td>
                                    <td>4</td>
                                    <td>2000</td>
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