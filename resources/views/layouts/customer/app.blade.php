<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Web font -->

    <!--begin::Global Theme Styles -->
    <link href="{{asset('customer/assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('customer/assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('customer/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />

{{--    <link href="{{asset('customer/assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />--}}

{{--    <link rel="shortcut icon" href="{{asset('customer/assets/demo/default/media/img/logo/favicon.ico')}}" />--}}
    @stack("css")
    <style>
        input{
            border-width: 2px !important;
        }
        select{
            border-width: 2px !important;
        }
        .select2{
            border-width: 2px !important;
        }
        .alert-dismissible{
            color: white !important;
            position: relative !important;
            top: 310px !important;

        }
        .m--font-brand{
            color: white !important;
        }
    </style>

</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

    <!-- BEGIN: Header -->
@include('layouts.customer.header')
<!-- Left side column. contains the logo and sidebar -->
    <!-- END: Header -->

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

        <!-- BEGIN: Left Aside -->
    @include('layouts.customer.left_sidebar')
    <!-- END: Left Aside -->
        <section class="content" style="    font-size: 14px; background-color: #f4f6f8;    width: -webkit-fill-available;">
            @yield('content')
        </section>
    </div>

    <!-- end:: Body -->

    <!-- begin::Footer -->
{{--@include('layouts.customer.footer')--}}
<!-- end::Footer -->
</div>

<!-- end:: Page -->

<!-- begin::Quick Sidebar -->
{{--@include('layouts.customer.right_sidebar')--}}
<!-- end::Quick Sidebar -->

<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>

<!--begin::Global Theme Bundle -->
<script src="{{asset('customer/assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('customer/assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>

{{--<script src="{{asset('customer/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>--}}

<script src="{{asset('customer/assets/app/js/dashboard.js')}}" type="text/javascript"></script>
{{--<script src="{{asset('customer/assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>--}}

{{--<script src="{{asset('customer/assets/demo/default/custom/crud/datatables/basic/basic.js')}}" type="text/javascript"></script>--}}
<!--end::Page Scripts -->

@stack('scripts')
</body>

<!-- end::Body -->
</html>