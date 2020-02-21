@extends('layouts.customer.app')

@section("title")
    Refurbisher Report
@endsection
@push("css")
    <style>
        .times{
            /*display:none;*/
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Report | Refurbisher</h3>
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
                <div class="m-portlet__body">
                   
                         <form action="{{url()->current()}}" method="post" > {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Select Refurbisher</label>
                                <select name="name" class="form-control">
                                    @foreach($user as $x)
                                    <option value="{{$x->id}}">{{$x->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Date</label>
                                <select name="date" class="form-control data">
                                    <option >Today</option>
                                    <option>7 Days</option>
                                    <option>Custom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 times">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Date To</label>
                                <input type="text"  class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2 times">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Date From</label>
                                <input type="text"  class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Type</label>
                                <select name="type" class="form-control">
                                    <option >Marry</option>
                                    <option>Repared</option>
                                    <option>Broken</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">submit</label>
                                <input type="submit" class="form-control btn btn-primary" name="sub">
                            </div>
                        </div>
                    </div>          
                    <!--<div class="row times"></div>-->
                    </form>
                    <hr>
                    <div class="row">
                        <table class="table table-stripted">
                            @if(!empty($data))
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>IMEI</th>
                                  
                                    <th>Parpard</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($data as $x)
                                <tr>
                                    <td>{{$x->model}}</td>
                                    <td>{{$x->imei}}</td>
                                    
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                     <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                @endforeach
                            </tbody>
                                @endif
                         @if(!empty($marry))
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>IMEI</th>
                                    <th>LCD</th>
                                    <th>Marry</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($marry as $x)
                                <tr>
                                    <td>{{$x->model}}</td>
                                    <td>{{$x->imei}}</td>
                                    <td>{{$x->barcode}}</td>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                 @endforeach
                            </tbody>
                                @endif
                               
                         @if(!empty($broken))
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    
                                    <th>LCD</th>
                                    <th>Broken</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($broken  as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    
                                    <td>{{$x->barcode}}</td>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                     <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                 @endforeach
                            </tbody>
                                @endif
                               
                            
                            
                            
                        </table>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <table class="table">
                            <tfoot class="text-center ">
                                <tr class="bg-primary text-white">
                                    <th colspan="1">Marry</th>
                                    <th colspan="1">Repared</th>
                                    <th colspan="1">Broken</th>
                                </tr>
                                <tr>
                                    <td colspan="1">{{$mar}}</td>
                                    <td colspan="1">{{$rep}}</td>
                                    <td colspan="1">{{$bro}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    
 {{--datetimepicker wirh moment js--}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script>
        $(document).ready(function () {
             $('.times').hide();
            $('#datepicker_from').datepicker({
                uiLibrary: 'bootstrap'
            });
            $('#datepicker_to').datepicker({
                uiLibrary: 'bootstrap'
            });
        });

        jQuery(document).ready(function(){
                $('.data').on('change', function() {
                var x =  $(this).find(":selected").val();
                if(x == 'Custom'){
                    $('.times').show();
                }else{
                    $('.times').hide();
                }
            });
           })
    </script>
@endpush