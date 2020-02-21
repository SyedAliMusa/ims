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
                                <label for="usr">Date</label>
                                <select name="date" class="form-control data">
                                    <option  >Today</option>
                                    <option <?php if(!empty($udata)) if($udata == '7 Days') echo 'selected'; ?> >7 Days</option>
                                    <option  <?php if(!empty($udata)) if($udata == 'Custom') echo 'selected'; ?> >Custom</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2 times">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Date To</label>
                                <input type="text"  class="form-control" id="datepicker_to" name="to" title="To range picker" placeholder="To" value=" <?php if(!empty($udata_to)) echo $udata_to; ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2 times">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Date From</label>
                                <input type="text"  class="form-control" id="datepicker_from" name="from" title="From" placeholder="From" value="<?php if(!empty($udata_from)) echo $udata_from; ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Refurbisher Type</label>
                                <select name="refurbisherType" class="form-control acc">
                                    <option value="LCD_Refurbished">Raniel</option>
                                    <option <?php if(!empty($urefurbisherType)) if($urefurbisherType == 'Phone_Refurbished') echo 'selected'; ?>   value="Phone_Refurbished">Phone Refurbisher</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 account">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Select Refurbisher</label>
                                <select name="name" class="form-control">
                                    @foreach($user as $x)
                                    <option <?php if(!empty($uname)) if($uname == $x->id) echo 'selected'; ?> value="{{$x->id}}">{{$x->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group margin-0 on_error">
                                <label for="usr">Type</label>
                                <select name="type" class="form-control">
                                    <option <?php if(!empty($utype)) if($utype == 'Released') echo 'selected'; ?> >Released</option>
                                    <option  <?php if(!empty($utype)) if($utype == 'Received') echo 'selected'; ?>>Received</option>
                                    <option <?php if(!empty($utype)) if($utype == 'Stuck') echo 'selected'; ?>>Stuck</option>
                                    <option <?php if(!empty($utype)) if($utype == 'Broken') echo 'selected'; ?>>Broken</option>
                                    <option <?php if(!empty($utype)) if($utype == 'Ready To Go') echo 'selected'; ?>>Ready To Go</option>
                                    <option <?php if(!empty($utype)) if($utype == 'Dispatch') echo 'selected'; ?>>Dispatch</option>
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
                            @if(!empty($bokn))
                            <thead>
                                <tr>
                                   <th>Model</th>
                                    <th>Barcode</th>
                                    <th>Broken</th>
                                    <!--<th>Marry</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($bokn as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    <td>{{$x->barcode}}</td>
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                @endforeach
                            </tbody>
                                @endif
                            @if(!empty($data))
                            <thead>
                                <tr>
                                   <th>Model</th>
                                    <th>Barcode</th>
                                    <th>Received</th>
                                    <!--<th>Marry</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($data as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    <td>{{$x->barcode}}</td>
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
                                    <th>Barcode</th>
                                    <th>Released</th>
                                    <!--<th>Marry</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($marry as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    <td>{{$x->barcode}}</td>
                                    <!--<td>{{$x->barcode}}</td>-->
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <?php if($utype == 'Released' &&  $urefurbisherType == 'LCD_Refurbished'){ ?>
                                    <td><?=date("m-d-Y",strtotime($x->created_at)) ?></td>
                                    <?php }else{ ?>
                                    <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                    <?php } ?>
                                </tr>
                                 @endforeach
                            </tbody>
                                @endif
                               
                         @if(!empty($ready) && $utype == 'Ready To Go')
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Barcode</th>
                                    <th>Ready to go</th>
                                    <!--<th>Marry</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($ready as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    <td>{{$x->barcode}}</td>
                                    <!--<td>{{$x->barcode}}</td>-->
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                 @endforeach
                            </tbody>
                                @endif
                         @if(!empty($dispatch) && $utype == 'Dispatch')
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Barcode</th>
                                    <th>Dispatch</th>
                                    <!--<th>Marry</th>-->
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($dispatch as $x)
                                <tr>
                                    <td>{{$x->modal}}</td>
                                    <td>{{$x->barcode}}</td>
                                    <!--<td>{{$x->barcode}}</td>-->
                                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                    <td><?=date("m-d-Y",strtotime($x->updated_at)) ?></td>
                                </tr>
                                 @endforeach
                            </tbody>
                                @endif
                               
                         @if(!empty($stuckCount) &&  $utype == 'Stuck')
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    
                                    <th>LCD</th>
                                    <th>Stuck</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($stuckCount  as $x)
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
                                    <th colspan="1">Released</th>
                                <?php if(!empty($utype)) if( $urefurbisherType == 'LCD_Refurbished'){ ?>
                                    <td colspan="1">Moved to Refurbisher</td>
                                <?php } ?>   
                                    <th colspan="1">Received</th>
                                    <th colspan="1">Stuck</th>
                                    <th colspan="1">Broken</th>
                                <?php if(!empty($utype)) if( $urefurbisherType == 'LCD_Refurbished'){ ?>
                                 <?php }else{ ?> 
                                    <th colspan="1">Ready To GO</th>
                                    <th colspan="1">Dispatch</th>
                                <?php } ?> 
                                </tr>
                                <tr> 
                                <?php if(!empty($stuckCount)) $bro=count($stuckCount); else $bro= '0';  ?>
                                <?php if(!empty($ready)) $r=count($ready); else $r='0'; ?>
                                <?php if(!empty($dispatch)) $diss = count($dispatch); else $diss = '0'; ?>
                                
                                <?php if(!empty($utype)) if($urefurbisherType == 'LCD_Refurbished'){ ?>
                                    <td colspan="1">{{$mar}}</td>
                                    <td colspan="1">{{$moved}}</td>
                                <?php }else{ ?>    
                                    <td colspan="1">{{$rep+$bro+$br+$r+$diss}}</td>
                                <?php } ?>
                                    <td colspan="1">{{$rep}}</td>
                                    <td colspan="1">{{$bro}}</td>
                                    <td colspan="1">{{$br}}</td>
                                     <?php if(!empty($utype)) if( $urefurbisherType == 'LCD_Refurbished'){ ?>
                                 <?php }else{ ?> 
                                    <td colspan="1">{{$r}}</td>
                                    <td colspan="1">{{$diss}}</td>
                                <?php } ?> 
                                    
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
             <?php if(!empty($urefurbisherType)){ if($urefurbisherType == 'Phone_Refurbished'){  ?>
              $('.account').show();
           <?php }else{ ?>
                $('.account').hide();
           <?php }}else{ ?>
                  $('.account').hide();
           <?php } ?>
               
           
            <?php if(!empty($udata)){ if($udata == 'Custom'){  ?>
                $('.times').show();
           <?php }else{ ?>
                 $('.times').hide();
           <?php }}else{ ?>
                 $('.times').hide();
           <?php } ?>
             
            // $udata == 'Custom'
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
            $('.acc').on('change', function() {
                var x =  $(this).find(":selected").val();
                if(x == 'LCD_Refurbished'){
                    $('.account').hide();
                }else{
                    $('.account').show();
                }
            });
           })
    </script>
@endpush