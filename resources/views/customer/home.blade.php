@extends('layouts.customer.app')

@section("title")
    Home
@endsection
@push("css")

    <style type="text/css">
.offer-card{
  /*border:1px solid black;*/
  border-radius: 0px;
}
.upper-section{
      /*border-bottom: 1px solid black;*/
    /*background: #a2d200;*/
    color: white;
}
.number{
  color: yellow;
}
.upper-section i{
  font-size: 40px;
}
.card a{
    font-weight:300;
    /*color:black !important;*/
}
.box-bg{
   background: white;
   padding: 20px 20px;
   box-shadow: -3px 1px 20px lightgrey;
}
a:hover{
   text-decoration: none;
}

</style>
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <!--<h3 class="m-subheader__title ">Dashboard</h3>-->
                </div>
                <div>
								<!--<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">-->
								<!--	<span class="m-subheader__daterange-label">-->
								<!--		<span class="m-subheader__daterange-title">Today:</span>-->
								<!--		<span class="m-subheader__daterange-date m--font-brand">Dec 13</span>-->
								<!--	</span>-->
								<!--	<a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">-->
								<!--		<i class="la la-angle-down"></i>-->
								<!--	</a>-->
								<!--</span>-->
                </div>
            </div>
        </div>

        <!-- END: Subheader -->
        <div class="m-content">
             @if (auth()->user()->account_type == 'admin' || auth()->user()->id == '43')
            
            <!--first part-->
             <div class="row box-bg">
                  <div class="col-md-3">
                    <div class="card offer-card border-info" >
                         <a  href="http://ims.cellgalleryonline.com/beta/lots" >
                    <div class="row upper-section bg-info py-3 m-auto" style="width:100%;">
                    <div class="col-md-4 text-right pt-3">
                      <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="col-md-8">
                     
                        <h4 class="number">{{$lot}}</h4>
                        <h4 class="text">LOTS</h4>
                     
                    </div>
                  </div>
                   </a>
                   <a   href="http://ims.cellgalleryonline.com/beta/lots/create">
              <div class="card-body py-2">
             <p class="mb-0 font-weight-bold text-info text-center">Create LOT</p>
              </div>
              </a>
            </div>
                  </div>
                   <div class="col-md-3">
                    <div class="card offer-card border-success" >
                        <a  href="http://ims.cellgalleryonline.com/beta/inventory" >
               <div class="row upper-section bg-success py-3 m-auto" style="width:100%;">
                    <div class="col-md-4 text-right pt-3">
                      <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="col-md-8">
                        
                      <h4 class="number">{{$inventory}}</h4>
                      <h4 class="text">Inventory</h4>
                     
                    </div>
                  </div>
                   </a>
                   <a href="http://ims.cellgalleryonline.com/beta/inventory/create">
              <div class="card-body py-2">
             <p class="mb-0 font-weight-bold text-success text-center">Create Inventory</p>
              </div>
              </a>
            </div>
                  </div>
                   <div class="col-md-3">
                    <div class="card offer-card border-warning" >
                         <a href="http://ims.cellgalleryonline.com/beta/dispatch" >
               <div class="row upper-section bg-warning py-3 m-auto" style="width:100%;">
                    <div class="col-md-4 text-right pt-3">
                      <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="col-md-8">
                       
                      <h4 class="number">{{$dispatch}}</h4>
                      <h4 class="text">Dispatch</h4>
                    </div>
                  </div>
                      </a>
                      <a  href="http://ims.cellgalleryonline.com/beta/dispatch/create">
              <div class="card-body py-2">
             <p class="mb-0 font-weight-bold text-warning text-center">Creat Dispatch</p>
              </div>
              </a>
            </div>
                  </div>
                   <div class="col-md-3">
                    <div class="card offer-card border-danger" >
                        <a  href="http://ims.cellgalleryonline.com/beta/returns" >
               <div class="row upper-section bg-danger py-3 m-auto" style="width:100%;">
                    <div class="col-md-4 text-right pt-3">
                      <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="col-md-8">
                      <h4 class="number">{{$dispatchR}}</h4>
                      <h4 class="text"> Return</h4>
                    </div>
                  </div>
                      </a>
                      <a href="http://ims.cellgalleryonline.com/beta/returns/create">
              <div class="card-body py-2">
             <p class="mb-0 font-weight-bold text-danger text-center">Create Return</p>
              </div>
              </a>
            </div>
                  </div>
                </div>
            <!--first part-->
            <br>
            
             <div class="row text-center">
                
                 <div class="col-md-6 box-bg">
                     <div class="row">
                     <div class="col-md-12 mb-2">
                    <h1>Phone Warehouse</h1>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/warehouse/in_out?release=true">
                        <div class="card  btn-outline-primary py-5">
                            <h2>Release</h2>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/warehouse/in_out">
                        <div class="card btn-outline-success py-5 mb-4">
                            <h2>Receive</h2>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a href="http://ims.cellgalleryonline.com/beta/red_flag">
                        <div class="card  btn-outline-danger py-5">
                            <h2>Pending IMEI</h2>
                        </div>
                        </a>
                    </div>
                    </div>
                 </div>
                 <div class="col-md-6 box-bg">
                      <div class="row">
                     <div class="col-md-12 mb-2">
                    <h1>LCD Warehouse</h1>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/issue_lcd">
                        <div class="card  btn-outline-primary py-4">
                            <h2>Release & Receive</h2>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/broken_lcd">
                        <div class="card  btn-outline-success py-5 mb-4">
                            <h2>Broken LCD</h2>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/lcd_inventory/print_barcode">
                        <div class="card  btn-outline-danger py-5">
                            <h2>Print Barcode</h2>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a  href="http://ims.cellgalleryonline.com/beta/refurbisherLcdReport">
                        <div class="card   btn-outline-warning py-5">
                            <h2>Pending LCD</h2>
                        </div>
                        </a>
                    </div>
                 </div>
                 </div>
              
            </div>    
            
            <br>
            
            <!--chart section start-->
            
            <div class="row mb-2">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                  google.charts.load('current', {'packages':['corechart']});
                  google.charts.setOnLoadCallback(drawChart);
                  google.charts.setOnLoadCallback(drawChart2);
            
                  function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                      ['Date', 'Kathy', 'Branda'],
                      ['<?php echo date('D',strtotime("-7 days")) ?>',  <?= $testK['d7'] ?>,      <?= $testB['d7'] ?>],
                      ['<?php echo date('D',strtotime("-6 days")) ?>',  <?= $testK['d6'] ?>,      <?= $testB['d6'] ?>],
                      ['<?php echo date('D',strtotime("-5 days")) ?>',  <?= $testK['d5'] ?>,      <?= $testB['d5'] ?>],
                      ['<?php echo date('D',strtotime("-4 days")) ?>',  <?= $testK['d4'] ?>,      <?= $testB['d4'] ?>],
                      ['<?php echo date('D',strtotime("-3 days")) ?>',  <?= $testK['d3'] ?>,      <?= $testB['d3'] ?>],
                      ['<?php echo date('D',strtotime("-2 days")) ?>',  <?= $testK['d2'] ?>,      <?= $testB['d2'] ?>],
                      ['<?php echo date('D',strtotime("-1 days")) ?>',  <?= $testK['d1'] ?>,      <?= $testB['d1'] ?>]
                      
                    ]);
            
                    var options = {
                      title: 'Tester Performance',
                      curveType: 'function',
                      legend: { position: 'bottom' }
                    };
            
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            
                    chart.draw(data, options);
                  }
                  function drawChart2() {
                    var data = google.visualization.arrayToDataTable([
                      ['Date', 'Sindy', 'Fatima'],
                      ['<?php echo date('D',strtotime("-7 days")) ?>',  <?= $refS['d7'] ?>,      <?= $refF['d7'] ?>],
                      ['<?php echo date('D',strtotime("-6 days")) ?>',  <?= $refS['d6'] ?>,      <?= $refF['d6'] ?>],
                      ['<?php echo date('D',strtotime("-5 days")) ?>',  <?= $refS['d5'] ?>,      <?= $refF['d5'] ?>],
                      ['<?php echo date('D',strtotime("-4 days")) ?>',  <?= $refS['d4'] ?>,      <?= $refF['d4'] ?>],
                      ['<?php echo date('D',strtotime("-3 days")) ?>',  <?= $refS['d3'] ?>,      <?= $refF['d3'] ?>],
                      ['<?php echo date('D',strtotime("-2 days")) ?>',  <?= $refS['d2'] ?>,      <?= $refF['d2'] ?>],
                      ['<?php echo date('D',strtotime("-1 days")) ?>',  <?= $refS['d1'] ?>,      <?= $refF['d1'] ?>]
                    ]);
            
                    var options = {
                      title: 'Refurbisher Performance',
                      curveType: 'function',
                      legend: { position: 'bottom' }
                    };
            
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));
            
                    chart.draw(data, options);
                  }
                </script>
                 <div class="col-md-6">
                    <div id="curve_chart" style="width: 100%; height: 400px"></div>
                 </div>
                 <div class="col-md-6">
                    <div id="curve_chart2" style="width: 100%; height: 400px"></div>
                 </div>
                 
            </div>
            <!--chart section end-->

            <br>
            <br>
            <!--report center-->
            <div class="row text-center box-bg">
                <div class="col-md-12 mb-5">
                    <h1>Report Center</h1>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/lot">
                    <div class="card  btn-outline-primary py-5">
                        <h2>Lot Report</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/model_sales">
                    <div class="card  btn-outline-success py-4">
                        <h2>Model Sale Report</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/model_summary_2">
                    <div class="card  btn-outline-warning py-4">
                        <h2>Model Summary Report</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/asin">
                    <div class="card  btn-outline-danger py-5 mb-4">
                        <h2>Asin Report</h2>
                    </div>
                    </a>
                </div>
              
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/tester">
                    <div class="card  btn-outline-info py-5">
                        <h2>Test Report</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/report/dispatch">
                    <div class="card  btn-outline-primary py-5">
                        <h2>Dispatch Report</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a  href="http://ims.cellgalleryonline.com/beta/red_flag">
                    <div class="card  btn-outline-dark py-5">
                        <h2>Imei In Progress</h2>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <a href="http://ims.cellgalleryonline.com/beta/refurbisherLcdReport">
                    <div class="card  btn-outline-primary py-4">
                        <h2>LCD refurbisher Report</h2>
                    </div>
                    </a>
                </div>
                
                </div>
            </div>            
            <!--report center-->
            <br>
            <br>
            @endif
            <!--Begin::Section-->
            <div class="row">
                <div class="col-xl-4">

                    <!--begin:: Widgets/Top Products-->
                    <!--<div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">-->
                    <!--    <div class="m-portlet__head">-->
                    <!--        <div class="m-portlet__head-caption">-->
                    <!--            <div class="m-portlet__head-title">-->
                    <!--                <h3 class="m-portlet__head-text">-->

                    <!--                </h3>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <div class="m-portlet__head-tools">-->
                    <!--            <ul class="m-portlet__nav">-->
                    <!--                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">-->
                    <!--                    <a href="#" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">-->
                    <!--                        All-->
                    <!--                    </a>-->
                    <!--                    <div class="m-dropdown__wrapper">-->
                    <!--                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36.5px;"></span>-->
                    <!--                        <div class="m-dropdown__inner">-->
                    <!--                            <div class="m-dropdown__body">-->
                    <!--                                <div class="m-dropdown__content">-->
                    <!--                                    <ul class="m-nav">-->
                    <!--                                        <li class="m-nav__item">-->
                    <!--                                            <a href="" class="m-nav__link">-->
                    <!--                                                <i class="m-nav__link-icon flaticon-share"></i>-->
                    <!--                                                <span class="m-nav__link-text">Activity</span>-->
                    <!--                                            </a>-->
                    <!--                                        </li>-->
                    <!--                                        <li class="m-nav__item">-->
                    <!--                                            <a href="" class="m-nav__link">-->
                    <!--                                                <i class="m-nav__link-icon flaticon-chat-1"></i>-->
                    <!--                                                <span class="m-nav__link-text">Messages</span>-->
                    <!--                                            </a>-->
                    <!--                                        </li>-->
                    <!--                                        <li class="m-nav__item">-->
                    <!--                                            <a href="" class="m-nav__link">-->
                    <!--                                                <i class="m-nav__link-icon flaticon-info"></i>-->
                    <!--                                                <span class="m-nav__link-text">FAQ</span>-->
                    <!--                                            </a>-->
                    <!--                                        </li>-->
                    <!--                                        <li class="m-nav__item">-->
                    <!--                                            <a href="" class="m-nav__link">-->
                    <!--                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>-->
                    <!--                                                <span class="m-nav__link-text">Support</span>-->
                    <!--                                            </a>-->
                    <!--                                        </li>-->
                    <!--                                    </ul>-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </li>-->
                    <!--            </ul>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="m-portlet__body">-->

                            <!--begin::Widget5-->
                    <!--        <div class="m-widget4">-->
                    <!--            <div class="m-widget4__chart m-portlet-fit--sides m--margin-top-10 m--margin-top-20" style="height:260px;"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>-->
                    <!--                <canvas id="m_chart_trends_stats" width="386" height="260" class="chartjs-render-monitor" style="display: block; width: 386px; height: 260px;"></canvas>-->
                    <!--            </div>-->


                    <!--        </div>-->

                            <!--end::Widget 5-->
                    <!--    </div>-->
                    <!--</div>-->

                    <!--end:: Widgets/Top Products-->
                </div>
                <div class="col-xl-4">

                    <!--begin:: Widgets/Activity-->
              <!--      <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">-->
              <!--          <div class="m-portlet__head">-->
              <!--              <div class="m-portlet__head-caption">-->
              <!--                  <div class="m-portlet__head-title">-->
              <!--                      <h3 class="m-portlet__head-text m--font-light">-->
              <!--                          Stock Availability-->
              <!--                      </h3>-->
              <!--                  </div>-->
              <!--              </div>-->
              <!--              <div class="m-portlet__head-tools">-->
              <!--                  <ul class="m-portlet__nav">-->
              <!--                      <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover">-->
              <!--                          <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl">-->
              <!--                              <i class="fa fa-genderless m--font-light"></i>-->
              <!--                          </a>-->
              <!--                          <div class="m-dropdown__wrapper">-->
              <!--                              <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>-->
              <!--                              <div class="m-dropdown__inner">-->
              <!--                                  <div class="m-dropdown__body">-->
              <!--                                      <div class="m-dropdown__content">-->
              <!--                                      </div>-->
              <!--                                  </div>-->
              <!--                              </div>-->
              <!--                          </div>-->
              <!--                      </li>-->
              <!--                  </ul>-->
              <!--              </div>-->
              <!--          </div>-->
              <!--          <div class="m-portlet__body">-->
              <!--              <div class="m-widget17">-->
              <!--                  <div class="m-widget17__visual m-widget17__visual--chart m-portlet-fit--top m-portlet-fit--sides m--bg-danger">-->
              <!--                      <div class="m-widget17__chart" style="height:320px;"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>-->
              <!--                          <canvas id="m_chart_activities" width="386" height="216" class="chartjs-render-monitor" style="display: block; width: 386px; height: 216px;"></canvas>-->
              <!--                      </div>-->
              <!--                  </div>-->
              <!--                  <div class="m-widget17__stats">-->
              <!--                      <div class="m-widget17__items m-widget17__items-col1">-->
              <!--                          <div class="m-widget17__item">-->
														<!--<span class="m-widget17__icon">-->
														<!--	<i class="flaticon-time m--font-brand"></i>-->
														<!--</span>-->
              <!--                              <span class="m-widget17__subtitle">-->
														<!--	Available-->
														<!--</span>-->
              <!--                              <span class="m-widget17__desc">-->
              <!--                                  <b class="text-success">{{\App\Inventory::where('status','=', 1)->count()}}</b>-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                          <div class="m-widget17__item">-->
														<!--<span class="m-widget17__icon">-->
														<!--	<i class="flaticon-paper-plane m--font-info"></i>-->
														<!--</span>-->
              <!--                              <span class="m-widget17__subtitle">-->
														<!--	Returned-->
														<!--</span>-->
              <!--                              <span class="m-widget17__desc">-->
              <!--                                  <b class="text-info">{{\App\Returns::count()}}</b>-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                      </div>-->
              <!--                      <div class="m-widget17__items m-widget17__items-col2">-->
              <!--                          <div class="m-widget17__item">-->
														<!--<span class="m-widget17__icon">-->
														<!--	<i class="flaticon-pie-chart m--font-success"></i>-->
														<!--</span>-->
              <!--                              <span class="m-widget17__subtitle">-->
														<!--	Sold-->
														<!--</span>-->
              <!--                              <span class="m-widget17__desc">-->
              <!--                                  <b class="text-danger">{{\App\Inventory::where('status','=', 0)->count()}}</b>-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                          <div class="m-widget17__item">-->
														<!--<span class="m-widget17__icon">-->
														<!--	<i class="flaticon-time m--font-danger"></i>-->
														<!--</span>-->
              <!--                              <span class="m-widget17__subtitle">-->
														<!--	Bought-->
														<!--</span>-->
              <!--                              <span class="m-widget17__desc">-->
              <!--                                  <b class="text-primary">{{\App\Inventory::count()}}</b>-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                      </div>-->
              <!--                  </div>-->
              <!--              </div>-->
              <!--          </div>-->
              <!--      </div>-->

                    <!--end:: Widgets/Activity-->
                </div>
                <div class="col-xl-4">

                    <!--begin:: Widgets/Blog-->
              <!--      <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">-->
              <!--          <div class="m-portlet__head m-portlet__head--fit">-->
              <!--              <div class="m-portlet__head-caption">-->
              <!--                  <div class="m-portlet__head-action">-->
              <!--                      <button type="button" class="btn btn-sm m-btn--pill  btn-brand">Blog</button>-->
              <!--                  </div>-->
              <!--              </div>-->
              <!--          </div>-->
              <!--          <div class="m-portlet__body">-->
              <!--              <div class="m-widget19">-->
              <!--                  <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides" style="min-height-: 286px">-->
              <!--                      <img src="assets/app/media/img//blog/blog1.jpg" alt="">-->
              <!--                      <h3 class="m-widget19__title m--font-light">-->
              <!--                          Introducing New Feature-->
              <!--                      </h3>-->
              <!--                      <div class="m-widget19__shadow"></div>-->
              <!--                  </div>-->
              <!--                  <div class="m-widget19__content">-->
              <!--                      <div class="m-widget19__header">-->
              <!--                          <div class="m-widget19__user-img">-->
              <!--                              <img class="m-widget19__img" src="assets/app/media/img//users/user1.jpg" alt="">-->
              <!--                          </div>-->
              <!--                          <div class="m-widget19__info">-->
														<!--<span class="m-widget19__username">-->
														<!--	Anna Krox-->
														<!--</span><br>-->
              <!--                              <span class="m-widget19__time">-->
														<!--	UX/UI Designer, Google-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                          <div class="m-widget19__stats">-->
														<!--<span class="m-widget19__number m--font-brand">-->
														<!--	18-->
														<!--</span>-->
              <!--                              <span class="m-widget19__comment">-->
														<!--	Comments-->
														<!--</span>-->
              <!--                          </div>-->
              <!--                      </div>-->
              <!--                      <div class="m-widget19__body">-->
              <!--                          Lorem Ipsum is simply dummy text of the printing and typesetting industry scrambled it to make text of the printing and typesetting industry scrambled a type specimen book text of the dummy text of the printing printing and typesetting-->
              <!--                          industry scrambled dummy text of the printing.-->
              <!--                      </div>-->
              <!--                  </div>-->
              <!--                  <div class="m-widget19__action">-->
              <!--                      <button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--hover-brand m-btn--custom">Read More</button>-->
              <!--                  </div>-->
              <!--              </div>-->
              <!--          </div>-->
              <!--      </div>-->

                    <!--end:: Widgets/Blog-->
                </div>
            </div>

            <!--End::Section-->



            <!--End::Section-->
        </div>
    </div>
@stop
@push('scripts')
    <script>

    </script>
@endpush