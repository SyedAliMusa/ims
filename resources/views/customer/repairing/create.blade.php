@extends('layouts.customer.app')

@section("title")
    Testing | Create
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Repairing | Create</h3>
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
                    <h4 class="text-danger">{{session('message')}}</h4>

                    <form action="{{route('repairing.store')}}" method="post" class="form-horizontal"role="form" id="submit_form">
                        {{csrf_field()}}

                        {{-- @if ($product)
                             <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group margin-0">
                                         <label for="usr">IMEI</label>
                                         <input type="hidden" class="form-control" autofocus name="imei" id="imei_id_val" value="{{$product->inventory->imei}}" required>
                                         <input type="text" class="form-control" value="{{$product->inventory->imei}}" required disabled>
                                     </div>
                                 </div>
                                 <div class="col-md-4"></div>
                             </div>
                             <div class="row add_inventory">

                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

                                     <div class="form-group margin-0">
                                         <label for="usr">Brand</label>
                                         <input type="text" class="form-control" id="brand" disabled value="{{$product->inventory->lot->brand->name}}" required>
                                     </div>
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                     <div class="form-group margin-0">
                                         <label for="pwd">Model</label>
                                         <input type="text" class="form-control" id="model" disabled value="{{$product->inventory->lot->model}}" required>
                                     </div>
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                     <div class="form-group margin-0">
                                         <label for="pwd">Network</label>
                                         <input type="text" class="form-control" id="network" disabled value="{{$product->inventory->lot->network->name}}" required>
                                     </div>
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                     <div class="form-group margin-0">
                                         <label for="pwd">Storage</label>
                                         <input type="text" class="form-control" id="storage" disabled value="{{$product->inventory->lot->storage->name}}" required>
                                     </div>
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                     <div class="form-group margin-0">
                                         <label for="pwd">Color</label>
                                         <input type="text" class="form-control" id="color" disabled value="{{$product->inventory->lot->color}}" required>
                                     </div>
                                 </div>
                                 <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                     <div class="form-group margin-0">
                                         <label for="pwd">inventory_Category</label>
                                         <input type="text" class="form-control" id="category" disabled value="{{$product->inventory->category->name}}" required>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <table class="table table-bordered table-hover">
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
                                      <div class="oth">
                                        <textarea class="form-control" >Other Reason</textarea>
                                    </div>
                                    <br>
                                    <br>
                                 </div>
                             </div>
                         @else--}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group margin-0">
                                    <label for="usr">IMEssI</label>
                                    <input type="text" class="form-control" autofocus name="imei" id="imei_id_val" onchange="getLotByimei()" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="15" value="" required>

                                    <span id="return_date"></span>
                                    <span id="return_message"></span>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row add_inventory">
                            {{--<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" id="brand" disabled value="" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" id="model" disabled value="" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Network</label>
                                    <input type="text" class="form-control" id="network" disabled value="" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <input type="text" class="form-control" id="storage" disabled value="" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" id="color" disabled value="" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">inventory_Category</label>
                                    <input type="text" class="form-control" id="category" disabled value="" required>
                                </div>
                            </div>--}}
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
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
                                 <div class="oth">
                                        <textarea class="form-control" >Other Reason</textarea>
                                    </div>
                                    <br>
                                    <br>
                            </div>
                        </div>
                        {{--@endif--}}

                        <div class="row">
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary" style="width: 100%">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
@stop
@push('scripts')
    <script>
    
        $('.oth').hide();
            $("input[type='checkbox']").on('click',function(){
               var x = $(this).val();
               if(x == "47-Other"){
                   $('.oth').toggle();
                   $('.oth textarea').attr('name', function(index, attr){
                        return attr == 47 ? null : 47;
                    });
                   $(this).attr('name', function(index, attr){
                        return attr == 47 ? null : 47;
                    });
               }
            })
    
    
        $(function () {
            $('form').on('submit', function() {
                var numberOfChecked = $('input:checkbox:checked').length;
                console.log(numberOfChecked)
                if (numberOfChecked < 1) {
                    // alert('Checkbox not selected!')
                    return false;
                }
            });
        });
        function getLotByimei() {
            var imei_no = $('#imei_id_val').val();
            $.ajax({
                type: "GET",
                url: '{{route("lot_by_imei")}}/' + imei_no,
                success: function (data) {
                    console.log(data);
                    if(data == 'not found in history'){
                        $('.add_inventory').html('<div class="form-group margin-0 text-danger" style="margin: -4% 35% !important;"> Not found in History! </div>')
                    }
                    else if(data['testing_id']){
                        // window.location.href = '/testing/'+data['testing_id']+'/edit'
                    }
                    else if(data['brand']) {
                        console.log(data)

                        $('input[id=brand]').val(data['brand'])
                        $('input[id=model]').val(data['model'])
                        $('input[id=network]').val(data['network'])
                        $('input[id=storage]').val(data['storage'])
                        $('input[id=color]').val(data['color'])
                        $('input[id=category]').val(data['category'])

                        $('#return_date').val(data['return_date'])
                        $('#return_message').val(data['return_message'])
                    }
                    else{
                        $('.add_inventory').html('<div class="form-group margin-0 text-danger" style="margin: -4% 35% !important;"> Please release this phone from warehouse then perform an action! </div>')
                    }
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#datepicker_from').datepicker({
                uiLibrary: 'bootstrap'
            });
            $('#datepicker_to').datepicker({
                uiLibrary: 'bootstrap'
            });
        });

    </script>


@endpush