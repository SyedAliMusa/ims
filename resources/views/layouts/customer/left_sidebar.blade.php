<aside class="main-sidebar">
    <style>
        .m-aside-menu .m-menu__nav>.m-menu__section {
            margin: 10px 0 0 0 !important;
            height: 0px !important;
        }
    </style>
    <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
    <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

        <!-- BEGIN: Aside Menu -->
        <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
            @if (!Auth::guest())
                <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                    @if (auth()->user()->account_type == 'admin')
                        <li class="m-menu__item  m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><i class="m-menu__link-icon flaticon-business"></i><span
                                        class="m-menu__link-text">Attributes</span><i class="m-menu__hor-arrow la la-angle-right"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right"><span class="m-menu__arrow "></span>
                                <ul class="m-menu__subnav">
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 4)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('network.index')}}" class="m-menu__link "><span class="m-menu__link-text">Networks</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 2)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('store.index')}}" class="m-menu__link "><span class="m-menu__link-text">Storage</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 1)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('brand.index')}}" class="m-menu__link "><span class="m-menu__link-text">Brands</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 3)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('category.index')}}" class="m-menu__link "><span class="m-menu__link-text">Category </span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 6)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('stock-adjustment.index')}}" class="m-menu__link "><span class="m-menu__link-text">Stock Adjustment </span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 5)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('models.index')}}" class="m-menu__link "><span class="m-menu__link-text">Models</span></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('Search-Unlock-Codes')}}" class="m-menu__link "><span class="m-menu__link-text"><i class="m-menu__link-icon flaticon-search"></i><span class="m-menu__link-text">Search Unlock Codes </span></span></a></li>

                    @endif
                    {{--Inventory Management--}}
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 8)->first())
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text text-primary">Inventory Management</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('lots.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Lots</span>
                            </a>
                        </li>
                    @endif
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 9)->first())
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('inventory.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Inventory</span>
                            </a>
                        </li>
                    @endif
                    {{--SALES MANAGEMENT--}}
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 11)->first())
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text text-primary">SALES MANAGEMENT</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('dispatch.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Dispatch</span>
                            </a>
                        </li>
                    @endif
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 12)->first())
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('returns.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Dispatch Return</span>
                            </a>
                        </li>
                    @endif

                    {{--OPERATIONS--}}
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 10)->first())
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text text-primary">OPERATIONS</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('testing.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Testing</span>
                            </a>
                        </li>
                    @endif
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 5)->first())
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('testing.test_record')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Test Record </span>
                            </a>
                        </li>
                    @endif
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 5)->first())
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('repairing.index')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Repairing</span>
                            </a>
                        </li>
                    @endif

                    {{--WAREHOUSE--}}
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 7)->first())
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text text-primary">WAREHOUSE</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><i class="m-menu__link-icon flaticon-business"></i><span
                                        class="m-menu__link-text">Phone Warehouse</span><i class="m-menu__hor-arrow la la-angle-right"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right"><span class="m-menu__arrow "></span>
                                <ul class="m-menu__subnav">
                                    <!--<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('warehouse.index')}}" class="m-menu__link "><span class="m-menu__link-text">Warehouse Items</span></a></li>-->
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('warehouse_in_out')}}?release=true" class="m-menu__link "><span class="m-menu__link-text">Release </span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('warehouse_in_out')}}" class="m-menu__link "><span class="m-menu__link-text">Receive </span></a></li>
                                    <!--<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('Search-Unlock-Codes')}}" class="m-menu__link "><span class="m-menu__link-text">Search Unlock Codes </span></a></li>-->
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (auth()->user()->account_type == 'admin' || auth()->user()->id == '43')
                        <li class="m-menu__item  m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><i class="m-menu__link-icon flaticon-business"></i><span
                                        class="m-menu__link-text">LCD Warehouse</span><i class="m-menu__hor-arrow la la-angle-right"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right"><span class="m-menu__arrow "></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.print_barcode')}}" class="m-menu__link "><span class="m-menu__link-text">Print LCD Barcodes</span></a></li>
                                    {{--<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.lcd_warehouse')}}" class="m-menu__link "><span class="m-menu__link-text">LCD Warehouse</span></a></li>--}}
                                    {{--<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.print_barcode')}}" class="m-menu__link "><span class="m-menu__link-text">Print LCD Barcode</span></a></li>--}}
                                    {{--<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.create')}}" class="m-menu__link "><span class="m-menu__link-text">Add LCD Inventory</span></a></li>--}}
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.issue_lcd')}}" class="m-menu__link "><span class="m-menu__link-text">LCD Release & Receive</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.broken_lcd')}}" class="m-menu__link "><span class="m-menu__link-text">LCD Broken</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.lcd_profile')}}" class="m-menu__link "><span class="m-menu__link-text">LCD History</span></a></li>
                                    @if (auth()->user()->account_type == 'admin' or auth()->user()->account_type == 'refurbishing' || auth()->user()->id == '43')
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('lcd_inventory.attach_imei_with_lcd')}}" class="m-menu__link "><span class="m-menu__link-text">Attach IMEI With LCD</span></a></li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @else
                    
                     @if (auth()->user()->account_type == 'tester')
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('testing.release_phone_for_refurbisher')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Release Phone</span>
                            </a>
                        </li>
                    @endif
                    
                    @if (auth()->user()->account_type == 'refurbishing')
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('lcd_inventory.lcd_profile')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">LCD History</span>
                            </a>
                        </li>
                         <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('lcd_inventory.phone_release_by_tester')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Phones from Testers </span>
                            </a>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                            <a href="{{route('lcd_inventory.phone_profile')}}" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-layers"></i>
                                <span class="m-menu__link-text">Phone History</span>
                            </a>
                        </li>
                       
                            {{--<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="{{route('lcd_inventory.issue_lcd')}}" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon flaticon-layers"></i>
                                    <span class="m-menu__link-text">Add LCD Broken</span>
                                </a>
                            </li>--}}


                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="{{route('repairing.index')}}" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon flaticon-layers"></i>
                                    <span class="m-menu__link-text">Repairing</span>
                                </a>
                            </li>
                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="{{route('lcd_inventory.attach_imei_with_lcd')}}" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon flaticon-layers"></i>
                                    <span class="m-menu__link-text">Attach IMEI With LCD</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    {{--Reports--}}
                    @if (auth()->user()->account_type == 'admin')
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text text-primary">Reports center</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><i class="m-menu__link-icon flaticon-business"></i><span
                                        class="m-menu__link-text">Reports</span><i class="m-menu__hor-arrow la la-angle-right"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right"><span class="m-menu__arrow "></span>
                                <ul class="m-menu__subnav">
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 5)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('cataloge.index')}}" class="m-menu__link "><span class="m-menu__link-text">Product Catalog</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.refurbisherReport')}}" class="m-menu__link "><span class="m-menu__link-text">Refurbisher Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.refurbisherLcdReport')}}" class="m-menu__link "><span class="m-menu__link-text">Refurbisher LCD Report</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 12)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.lot')}}" class="m-menu__link "><span class="m-menu__link-text">Lot Report</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 14)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.asin')}}" class="m-menu__link "><span class="m-menu__link-text">Asin Report</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 15)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.model_summary_2')}}" class="m-menu__link "><span class="m-menu__link-text">Model Summary Report 2</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 16)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.model_sales')}}" class="m-menu__link "><span class="m-menu__link-text">Model Sales Report</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 17)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.dispatch')}}" class="m-menu__link "><span class="m-menu__link-text">Dispatch Report</span></a></li>
                                    @endif
                                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 18)->first())
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.colorfolder')}}" class="m-menu__link "><span class="m-menu__link-text">Color Folder Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.tester')}}" class="m-menu__link "><span class="m-menu__link-text">Tester Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('warehouse.create')}}" class="m-menu__link "><span class="m-menu__link-text">Release & Receive Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.attachIMEIWithLCD')}}" class="m-menu__link "><span class="m-menu__link-text">IMEI With LCD Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.brokenListReport')}}" class="m-menu__link "><span class="m-menu__link-text">Broken LCD Report</span></a></li>
                                        <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('report.lcdInventoryReport')}}" class="m-menu__link "><span class="m-menu__link-text">LCD Inventory Report</span></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    {{--Settings--}}
                    <li class="m-menu__section ">
                        <h4 class="m-menu__section-text text-primary">Configurations</h4>
                        <i class="m-menu__section-icon flaticon-more-v2"></i>
                    </li>
                    @if(\App\UserPermissions::where('u_id','=', Auth::user()->id)->where('p_id','=', 19)->first())
                        <li class="m-menu__item  m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><i class="m-menu__link-icon flaticon-business"></i><span
                                        class="m-menu__link-text">Settings</span><i class="m-menu__hor-arrow la la-angle-right"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right"><span class="m-menu__arrow "></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('user.index')}}" class="m-menu__link "><span class="m-menu__link-text">Set Privileges</span></a></li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            @endif
        </div>

        <!-- END: Aside Menu -->
    </div>
</aside>