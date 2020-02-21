@extends('layouts.customer.app')

@section("title")
    Sales Returns | Create
@endsection
@push("css")
    {{--include internal css--}}
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Sales Returns | Create</h3>
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
                    <span id="sold_category"></span>
                    <form action="{{route('returns.store')}}" method="post" class="form-horizontal"
                          role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="imei" id="imei_id_val" onchange="get_imei_category()" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" autofocus required>
                                    <div class="form-group" style="margin-top: 4%">
                                        <label for="usr">Enter Reason</label>
                                        <textarea class="form-control" rows="8" name="comment" id="comment" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="form-control" name="category_id" required>
                                                    <option value="" selected>select category</option>
                                                    @foreach(\App\Category::all() as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" >
                                            <button type="submit" class="btn btn-primary" style="width: 100%"> Save </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row add_inventory">
                                    <table class="table table-bordered table-hover table-sm">
                                        <tbody>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="1" class="form-check-input"
                                                                      value="1-Charger Port">
                                                            1- Charger Port</label>
                                                    </div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="2" class="form-check-input"
                                                                      value="2-LCD">
                                                            2- LCD</label>
                                                    </div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="3" class="form-check-input"
                                                                      value="3-Buzzer">
                                                            3- Buzzer</label>
                                                    </div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="4" class="form-check-input"
                                                                      value="4-Speaker">
                                                            4- Speaker</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="5" class="form-check-input"
                                                                      value="5-Back Camera">
                                                            5- Back Camera</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="6" class="form-check-input"
                                                                      value="6-Front Camera">
                                                            6- Front Camera</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="7" class="form-check-input"
                                                                      value="7-SIM Card">
                                                            7- SIM Card</label></div></div></td>


                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="8" class="form-check-input"
                                                                      value="8-Volume key">
                                                            8- Volume key</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="9" class="form-check-input"
                                                                      value="9-No Power">
                                                            9- No Power</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="10" class="form-check-input"
                                                                      value="10-Signals">
                                                            10- Signals</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="11" class="form-check-input"
                                                                      value="11-Burn">
                                                            11- Burn</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="12" class="form-check-input"
                                                                      value="12-Bad Digitizer">
                                                            12- Bad Digitizer</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="13" class="form-check-input"
                                                                      value="13-W/D">
                                                            13- W/D</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="14" class="form-check-input"
                                                                      value="14-Memory Card">
                                                            14- Memory Card</label></div></div></td>

                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="15" class="form-check-input"
                                                                      value="15-Heater Jake">
                                                            15- Heater Jake</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="16" class="form-check-input"
                                                                      value="16-Wifi">
                                                            16- Wifi</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="17" class="form-check-input"
                                                                      value="17-BlueTooth">
                                                            17- BlueTooth</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="18" class="form-check-input"
                                                                      value="18-Sensor">
                                                            18- Sensor</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="19" class="form-check-input"
                                                                      value="19-Vibrator">
                                                            19- Vibrator</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="20" class="form-check-input"
                                                                      value="20-Mic">
                                                            20- Mic</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="21" class="form-check-input"
                                                                      value="21-Freeze">
                                                            21- Freeze</label></div></div></td>
                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="22" class="form-check-input"
                                                                      value="22-Qc">
                                                            22- Qc</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="23" class="form-check-input"
                                                                      value="23-Battery">
                                                            23- Battery</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="24" class="form-check-input"
                                                                      value="24-Data Wipe">
                                                            24- Data Wipe</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="25" class="form-check-input" value="25-Data Wipe Verification">
                                                            25- Data Wipe Verification</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="26" class="form-check-input"
                                                                      value="26-Stylus">
                                                            26- Stylus</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="27" class="form-check-input"
                                                                      value="27-Power Key">
                                                            27- Power Key</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="28" class="form-check-input"
                                                                      value="28-Broken Housing">
                                                            28- Broken Housing</label></div></div></td>
                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="29" class="form-check-input"
                                                                      value="29-Finger Sencor">
                                                            29- Finger Sencor</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="30" class="form-check-input"
                                                                      value="30-Home Button">
                                                            30- Home Button</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="31" class="form-check-input"
                                                                      value="31-Broken Glass">
                                                            31- Broken Glass</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="32" class="form-check-input"
                                                                      value="32-No Code">
                                                            32- No Code</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="33" class="form-check-input"
                                                                      value="33-Key Pack Light">
                                                            33- Key Pack Light</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="34" class="form-check-input"
                                                                      value="34-Broken Board">
                                                            34- Broken Board</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="35" class="form-check-input"
                                                                      value="35-Microsoft Account">
                                                            35- Microsoft Account</label></div></div></td>
                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="36" class="form-check-input"
                                                                      value="36-Google Account">
                                                            36- Google Account</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="37" class="form-check-input"
                                                                      value="37-Dirty LCD-Glass">
                                                            37- Dirty LCD-Glass</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="38" class="form-check-input"
                                                                      value="38-Bad Bord">
                                                            38- Bad Bord</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="39" class="form-check-input"
                                                                      value="39-Antenna Connector">
                                                            39- Antenna Connector</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="40" class="form-check-input"
                                                                      value="40-OverHeat">
                                                            40- OverHeat</label></div></div></td>

                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="41" class="form-check-input"
                                                                      value="41-Broken Connector">
                                                            41- Broken Connector</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="42"
                                                                      class="form-check-input" value="42-Back Cover">
                                                            42- Back Cover</label></div></div></td>
                                        </tr>
                                        <tr>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="43" class="form-check-input"
                                                                      value="43-Open LCD">
                                                            43- Open LCD</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="44" class="form-check-input"
                                                                      value="44-Open Digitizer">
                                                            44- Open Digitizer</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="45" class="form-check-input"
                                                                      value="45-Silence Button">
                                                            45- Silence Button</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="46" class="form-check-input"
                                                                      value="46-No Service">
                                                            46- No Service</label></div></div></td>
                                            <td><div class="form-group">
                                                    <div class="radio">
                                                        <label><input type="checkbox" name="47" class="form-check-input"
                                                                      value="47-Other">
                                                            47- Other</label></div></div></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            @if(Session::has('message'))
                                <h2 class="headline text-success "> {{session('message')}}</h2>
                            @endif
                            @if(Session::has('error'))
                                <h2 class="headline text-danger "> {{session('error')}}</h2>
                            @endif
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
<script>
    function get_imei_category(){
        var imei = $('#imei_id_val').val()
        $.ajax({
            type: "get",
            url: '{{route("get_imei_category")}}?imei='+imei,
            success: function (data) {
                console.log(data);
                /*var tab = []
                if (data['return_data'] != '' && data['return_data'] != null) {
                    var obj = data['return_data'];
                    var header = '<table class="table table-bordered"> ' +
                        '<thead> ' +
                        '<tr> ' +
                        '<td>Returned By</td> ' +
                        '<td>Reason</td> ' +
                        '<td>Returned Category</td> ' +
                        '<td>Returned Date</td> ' +
                        '</tr> ' +
                        '</thead> ' +
                        '<tbody> '
                    tab.push(header)
                    $.each( obj, function( key, value ) {
                        var body = '<tr> ' +
                            '<td>'+value['name']+'</td> ' +
                            '<td>'+value['name']+'</td> ' +
                            '<td>'+value['name']+'</td> ' +
                            '<td>'+value['name']+'</td> ' +
                            '</tr> '
                        tab.push(body)
                    });
                    var footer = '</tbody> ' +
                        '</table> '
                    tab.push(footer);
                }


alert()



console.log(tab)*/

               Swal.fire({
                    title: 'Product Found!!!',
                    timerProgressBar: true,
                    showConfirmButton: false,
                    imageUrl: 'https://img.icons8.com/doodle/48/000000/checkmark.png',
                    imageWidth: 80,
                    imageHeight: 80,
                    imageAlt: 'success',
                    timer: 500,
                })
                if (data['sold_category']) {
                    $("#imei_id_val").attr("type","hidden");
                var succes = '<div class="m-portlet__body"><div class="row"><div class="col-md-1"></div> ' +
                    '<div class="col-md-3"><label for="usr">IMEI Number</label><input type="text" class="form-control" autofocus name="justmodel" id="" onchange="" ' +
                    'value="'+imei+'" disabled required></div><div class="col-md-1"></div> ' +
                    '<div class="col-md-3"><label for="usr">Sold Category</label><input type="text" class="form-control" autofocus name="justmodel" id="" onchange="" ' +
                    'value="'+data['sold_category']+'" disabled required></div><div class="col-md-1"></div> ' +
                    '<div class="col-md-2"><label for="usr">Sold Count</label><input type="text" class="form-control" autofocus name="justmodel" id="" onchange="" ' +
                    'value="'+data['sold_count']+'" disabled required></div><div class="col-md-1"></div></div></div>'
                    $('#sold_category').html(succes)
                }
                else {
                    Swal.fire({
                        title: 'Not Sold Yet!!!',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        imageUrl: 'https://img.icons8.com/cute-clipart/64/000000/close-window.png',
                        imageWidth: 80,
                        imageHeight: 80,
                        imageAlt: 'Failed',
                    })
                    setTimeout(function(){
                        window.location.href = '/returns/create'
                    }, 1000);
                }
            }
        });
    }
</script>
@endpush