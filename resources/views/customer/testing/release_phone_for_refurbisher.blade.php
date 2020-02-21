@extends('layouts.customer.app')

@section("title")
    Stock Out
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .hide{
            display: none !important;
        }
        .account{
          //  display: none;
        }
        
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <div class="row">
                        {{--release--}}
                        
                            <div class="col-md-12">
                                <h4 class="text-danger">{{session('deleted')}}</h4>
                                <div class="row" style="margin: 3%;">
                                    <div class="col-md-8">
                                        <form method="post" action="{{route('warehouse.release_by_tester')}}" role="form" id="form_verify_imei">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-md-3">
                                                  
                                                    <div class="form-group">
                                                        <b class="text-primary">Issued To </b>
                                                        <select class="form-control select_tags" name="issued_to" required>
                                                            
                                                          
                                                            
                                                            <option value="Refurbisher" SELECTED>Refurbisher</option>
                                                            
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
                                                           <option value="{{$x->name}}">{{$x->name}}</option>
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
                                                        <small id="imei_exist" class="text-danger">{{session('fail_release')}}</small>
                                                        <small id="imei_exist" class="text-success">{{session('success_release')}}</small>
                                                        <small id="imei_exist" class="text-primary">{{session('already_verified')}}</small>

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