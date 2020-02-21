<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

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
    <link href="{{asset('customer/assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Page Vendors Styles -->

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
            top: -30px !important;

        }
        .m--font-brand{
            color: white !important;
        }
        .m-portlet{
            -webkit-box-shadow: none;
            box-shadow: none;
            background-color: inherit;
        }
    </style>

</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"
      style="background-color: #f4f6f8; background-image: url('https://accounts.shopify.com/assets/public-bg-f1569c69b2c655d65b1eecaaac9b5ccb03748b568cc1ae53440599f34776fc9a.svg')">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body" style="    padding-top: 7%;
    padding-left: 13%;
    padding-right: 13%;">
                    <div class="row">
                        <div class="col-md-4 offset-4" style="    background-color: white;
    padding: 4%;
    font-weight: bold;
    border: 1px solid gainsboro;
    border-radius: 3px;">
                            <p></p>
                            <h1 class="text-center">
                                <div class="form-group">
                                    <label for="sdfd" class=" control-label">
                                        <img width="250" src="{{asset('images/logo-cellular.png')}}"
                                                                                  class="img-fluid"
                                                                                  alt=""></label>
                                </div>

                            </h1>
                            <br>
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class=" control-label">E-Mail Address</label>

                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class=" control-label">Password</label>

                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary form-control">
                                                Login
                                            </button>

                                            {{--<a class="btn btn-link" href="{{ route('password.request') }}">
                                                Forgot Your Password?
                                            </a>--}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>

<!-- end::Body -->
</html>





{{--
@extends('layouts.customer.app')

@section("title")
    IMS | Login
@endsection
@push("css")
    --}}
{{--include internal css--}}{{--

@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body" style="padding: 7%">

                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="sdfd" class=" control-label"><img src="{{asset('images/logo-cellular.png')}}"
                                                                                  class="img-fluid"
                                                                                  alt=""></label>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class=" control-label">E-Mail Address</label>

                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class=" control-label">Password</label>

                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary form-control">
                                                Login
                                            </button>

                                            --}}
{{--<a class="btn btn-link" href="{{ route('password.request') }}">
                                                Forgot Your Password?
                                            </a>--}}{{--

                                        </div>
                                    </div>
                                </div>
                            </form>
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

@endpush--}}
