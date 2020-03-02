@extends('layouts.customer.app')

@section("title")
    Dispatch | Create
@endsection
@push("css")
    {{--include internal css--}}
    <style>
        .hide{
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto" style="    width: 65%;">
                    <h3 class="m-subheader__title">Dispatch | Create</h3>
                    <?php  $from =  strtotime(\Carbon\Carbon::now());
                    //                    $date_inc = strtotime("1 day", $from);
                    $from = date("Y-m-d", $from);
                    $total_imei = \App\Dispatch::where('created_at','>', $from)->count();
                    $tracking_ids = \App\Dispatch::where('created_at','>', $from)
                        ->distinct('tracking')->count('tracking'); ?>
                    <span style="margin-left: 15%;font-size: xx-large">IMEI: <b class="text-success" style="font-size: xx-large" id="total_imei">{{$total_imei}}</b><span style="margin-left: 13%"> Tracking:</span> <b class="text-primary" style="font-size: xx-large" id="tracking_ids">{{$tracking_ids}}</b></span>
                </div>
                <div>
                    <span class="m-subheader__daterange">
                                                                    <span><a class="btn btn-primary" href="{{route('ExportDispatchToDay')}}">CSV/EXCEL</a></span>

                        </span>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <h4 class="text-success">{{session('success')}}</h4>
                    <h4 class="text-warning">{{session('imei_not_tested')}}</h4>
                    <h4 class="text-danger">{{session('error')}}</h4>

                    <form class="form-horizontal" id="form_dispatch"
                          role="form" {{--onsubmit="beep()"--}}>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-2 m--margin-left-30"></div>
                            <div class="col-md-4 m--margin-left-30 hide">
                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" disabled  id="brand" value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" disabled id="model"value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Network</label>
                                    <input type="text" class="form-control" disabled id="network" id=""  value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <input type="text" class="form-control" disabled id="storage" id=""  value="" >
                                </div>
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" disabled  id="color" value="" required>
                                </div>
                            </div>
                            <div class="col-md-4 m--margin-left-30">
                                <div class="form-group margin-0 on_error">
                                    <label for="usr">IMEI</label>
                                    <input type="text" class="form-control" name="imei" id="imei_id_val" onchange="getLotByimei()" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required autofocus="autofocus">
                                    <small id="imei_exist" class="text-danger" style="font-size: x-large"></small>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">Category</label>
                                    <input type="text" class="form-control fa-bold font-weight-bold text-danger" disabled  id="category" value="" required >
                                </div>
                                <div class="form-group margin-0" id="tracking_id_llll">
                                    <label for="usr">Tracking_ID</label>
                                    <input type="text" class="form-control" tabindex="0" name="tracking_id" id="tracking_id" maxlength="30" value="" required>
                                    <!--<input type="hidden" class="form-control" tabindex="0" name="tracking_id" id="" maxlength="30" value="" required>-->
                                    <small id="imei_exist_tracking" class="text-danger" style="font-size: x-large"></small>
                                </div>
                                <div class="form-group margin-0">
                                    <label for="usr">save dispatch or add more IMEI</label>
                                    <button type="submit" class="btn btn-warning"  style="width: 100%">Save
                                        Dispatch</button>
                                </div>
                            </div>
                            <div class="col-md-3" style="    margin-top: 31px;">
                                <button type="button" id="addmore_btn" onclick="addmore()"  class="btn btn-primary hide btn-sm">add more </button>
                                <br>
                                <div id="addmore">
                                    <span  style="color: blue" id="imei_success"></span>
                                    <input type="hidden" name="enable_multi_imei" value="false">
                                </div>
                                {{--<span class="text-danger" id="imei_success"></span>--}}
                            </div>
                        </div>
                    </form>
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
        $(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                var imei_no = $('#imei_id_val').val();
                var track_id = $('#tracking_id').val();
                var match = true
                var checkboxes = document.getElementsByName('imies[]');
                $.ajax({
                    type: "GET",
                    url: '{{route("imei_match_with_tracking_id")}}/' + track_id,
                    success: function (data) {
                        console.log(data)
                       if (data == 'true') {
                           $.ajaxSetup({
                               headers: {
                                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                               }
                           });
                           $.ajax({
                               url: '{{route("dispatch.store")}}',
                               type: "POST",
                               data: $('#form_dispatch').serialize(),

                               success: function( _response ){
                                   // Handle your response..
                                   if (_response[2] == 'MatchIds'){
                                       $('#imei_id_val').focus();
                                       $('#imei_id_val').val('');
                                       $('#tracking_id').val('');
                                       $('.on_error').addClass('has-error')
                                       $('#imei_exist_tracking').html("Tracking Id contains imei number")
                                       beep()
                                       Swal({
                                           background: 'orange',
                                           type: 'error',
                                           title: 'Tracking Id contains imei number'
                                       })
                                   } else {
                                       $('#imei_id_val').focus();
                                       $('#total_imei').text(_response[0]);
                                       $('#tracking_ids').text(_response[1]);
                                       $('.on_error').removeClass('has-error')
                                       $('#addmore_btn').addClass('hide')
                                       $('#addmore_btn').removeClass('focused')
                                       $('#imei_exist_tracking').html("")
                                       $('#imei_exist').html("")
                                       $('input[id=brand]').val('')
                                       $('input[id=model]').val('')
                                       $('input[id=network]').val('')
                                       $('input[id=storage]').val('')
                                       $('input[id=color]').val('')
                                       $('input[id=category]').val('')
                                       $('#imei_success').html("")
                                       $('#tracking_id').val('');
                                   }
                               },
                               error: function(_response){
                                   // Handle error
                                   console.log(_response);
                               }
                           });
                       } else {
                           $('#tracking_id').focus();
                           $('#tracking_id').val('');
                           $('.on_error').addClass('has-error')
                           $('#imei_exist_tracking').html("Tracking Id contains imei number")
                           beep()
                           Swal({
                               allowEnterKey: false,
                               background: 'orange',
                               type: 'error',
                               title: 'Tracking Id contains imei number'
                           })
                       }
                    }
                });
            });
        })

        function beep() {
            var beep = (function () {
                var ctxClass = window.audioContext ||window.AudioContext || window.AudioContext || window.webkitAudioContext
                var ctx = new ctxClass();
                return function (duration, type, finishedCallback) {
                    duration = +duration;
                    // Only 0-4 are valid types.
                    type = (type % 5) || 0;
                    if (typeof finishedCallback != "function") {
                        finishedCallback = function () {};
                    }
                    var osc = ctx.createOscillator();
                    osc.type = type;
                    osc.connect(ctx.destination);
                    if (osc.noteOn) osc.noteOn(0);
                    if (osc.start) osc.start();
                    setTimeout(function () {
                        if (osc.noteOff) osc.noteOff(0);
                        if (osc.stop) osc.stop();
                        finishedCallback();
                    }, duration);

                };
            })();

            beep(400, 100, function () {
            });
        }
        function getLotByimei() {
            $('#imei_id_val').focus();

            var imei_no = $('#imei_id_val').val();
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_imei_for_dispatch")}}/' + imei_no,
                success: function (data) {
                    console.log(data)
                    if(data['already_dispatched']){
                        $('#imei_id_val').focus();
                        $('#imei_id_val').val('');
                        $('.on_error').addClass('has-error')
                        $('#imei_exist').html("Imei already dispatched! you can't dispatch it again")
                        beep()
                        Swal({
                            allowEnterKey: false,
                            background: "#D8735D",
                            textcolor: "#FFF",
                            type: 'error',
                            title: '<span style="color:#FFFFFF">Imei already dispatched! you can\'t dispatch it again</span>'
                        })
                    }
                    else if(data['not_tested']){
                        $('#imei_id_val').focus();
                        $('.on_error').addClass('has-error')
                        $('#imei_id_val').val('');
                        $('#imei_exist').html("Imei not tested!")
                        beep()
                        Swal({
                            allowEnterKey: false,
                            background: "#EEAEA0",
                            textcolor: "#FFF",
                            type: 'error',
                            title: 'Imei not tested!'
                        })
                    }
                    else if(data['not_found']){
                        $('#imei_id_val').focus();
                        $('.on_error').addClass('has-error')
                        $('#imei_id_val').val('');
                        $('#imei_exist').html("Imei not found!")
                        beep()
                        Swal({
                            allowEnterKey: false,
                            background: "#A55645",
                            textcolor: "#FFF",
                            type: 'error',
                            title: '<span style="color:#FFFFFF">Imei not found!</span>'
                        })
                    }
                    else if(data['not_released']){
                        $('#imei_id_val').focus();
                        $('.on_error').addClass('has-error')
                        $('#imei_id_val').val('');
                        $('#imei_exist').html("Imei is not yet Release!")
                        beep()
                        Swal({
                            allowEnterKey: false,
                            background: "#924F40",
                            textcolor: "#FFF",
                            type: 'error',
                            title: '<span style="color:#FFFFFF">Imei is not yet Release!</span>'
                        })
                    }
                    else if($('span#'+imei_no).html() == imei_no){
                        $('#imei_id_val').focus();
                        $('.on_error').addClass('has-error')
                        $('#imei_id_val').val('');
                        $('#imei_exist').html("Imei aleady added in the list!")
                        beep()
                        Swal({
                            allowEnterKey: false,
                            background: "#634A44",
                            textcolor: "#FFF",
                            type: 'error',
                            title: '<span style="color:#FFFFFF">Imei aleady added in the list!</span>'
                        })
                    }
                    else {
                        $('.on_error').removeClass('has-error')
                        $('#addmore_btn').removeClass('hide')
                        $('#imei_exist').html("")
                        $('input[id=brand]').val(data['brand'])
                        $('input[id=model]').val(data['model'])
                        $('input[id=network]').val(data['network'])
                        $('input[id=storage]').val(data['storage'])
                        $('input[id=color]').val(data['color'])
                        $('input[id=category]').val(data['category'])

                        if ( $('#addmore_btn').hasClass('focused')) {
                            $('#imei_id_val').focus();
                            $('#imei_id_val').val('');
                        }

                        $('#imei_success').append('<br><span id='+imei_no+' name = "add_more">' + imei_no + '</span><a id="cancel_btn'+imei_no+'" onclick="delete_wrongly_added_imei(' + imei_no + ')" style="cursor: pointer;color: red;">&nbsp; cancel</a>')
                        $('#imei_success').append('<input type="hidden" name="imies[]" value= "' + imei_no + '" id = "hidinput' + imei_no + '">')
                        $('input[name=imei]').val('')
                        $('input[name=enable_multi_imei]').val('addmore{{\Illuminate\Support\Facades\Auth::id()}}')
                        $('input[name=imei]').removeAttr('required')
                    }
                }
            });
        }

        //delete wrongly added imei
        function delete_wrongly_added_imei(imei){
            $('span[id^="'+imei+'"]').remove()
            $('a[id^="cancel_btn'+imei+'"]').remove()
            $('input[id^="hidinput'+imei+'"]').remove()
            $('#imei_id_val').val('')
        }
        function addmore() {

            $('#addmore_btn').addClass('focused')
            $('#imei_id_val').focus();
        }

    </script>
@endpush