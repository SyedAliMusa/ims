<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Testing;

Route::get('/clear', function () {

  \Illuminate\Support\Facades\Artisan::call('cache:clear');
  \Illuminate\Support\Facades\Artisan::call('view:clear');
  \Illuminate\Support\Facades\Artisan::call('route:clear');
  \Illuminate\Support\Facades\Artisan::call('config:clear');
  return redirect()->back();
});
Route::get('/testing_problem/{testing_id?}', function ($testing_id) {

    $problem_record = Testing::where('id','=',$testing_id)->first();
    $testing_ids = Testing::where('inventory_id','=',$problem_record->inventory_id)
        ->orderByDesc('id')
        ->get();
    return view('customer.testing.problem_updated',compact('testing_ids'));
});


Auth::routes();

Route::get('/report/color_folder', function () {
    $results = [];
    return view('customer.reports.color_folder', compact('results'));
})->name('report.colorfolder');
Route::get('/reports/colorbased', 'ReportController@getcolorbase')->name('colorfolder.store');
Route::get('/reports/getcolors', 'ReportController@getcolors')->name('getcolors');
Route::get('/reports/colors', function () {
    $results = [];
    return view('customer.reports.color', compact('results'));
})->name('color');
Route::get('/reports/single-color', 'ReportController@getsinglecolor')->name('single_color');
Route::post('/reports/single-color', 'ReportController@getsinglecolor')->name('single_color');
Route::get('/register', 'HomeController@getRegister')->name('register');
Route::get('/users', 'HomeController@getUsers')->name('users.data');
Route::post('/users/delete/{user_id}', 'HomeController@deleteUsers')->name('users.delete.id');
Route::get('/mail', 'HomeController@testMail')->name('mail');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/search', 'HomeController@search')->name('home.search');
Route::match(['GET', 'POST'], '/lots/addmore/{id?}', 'LotController@addMoreLot')->name('lots.addmore');
Route::resource("lots", "LotController");
Route::get("inventory/change/category/{id}", "InventoryController@changeCategory");
Route::get("inventory/quick_create", "InventoryController@Quick_create")->name('inventory.quick_create');
Route::resource("inventory", "InventoryController");
Route::resource("dispatch", "DispatchController");
Route::get("testing/test-again/{id?}", "TestingController@testAgain")->name('testing.test_again');
Route::get("/testRecord", "TestingController@testRecords")->name('testing.test_record');
Route::resource("testing", "TestingController");
Route::resource("repairing", "RepairingController");
Route::resource("returns", "ReturnController");
Route::resource("user", "UserController");
Route::resource("network", "NetworkController");
Route::resource("models", "ModelsController");
Route::resource("store", "StoreController");
Route::resource("brand", "BrandController");
Route::resource("cataloge", "CatalogeController");
Route::resource("category", "CategoryController");
Route::get("red_flag", "WareHouseController@redFlag")->name('red_flag');
Route::get("warehouse/in_out", "WareHouseController@warehouse_in_out")->name('warehouse_in_out');
Route::post("warehouse/in_out", "WareHouseController@warehouse_in_out")->name('warehouse_in_out');
Route::get("Search-Unlock-Codes", "WareHouseController@imei")->name('Search-Unlock-Codes');
Route::post("getimei", "WareHouseController@getimei")->name('getimei');
Route::post("storeimei", "WareHouseController@storeimei")->name('storeimei');
Route::resource("warehouse", "WareHouseController");
Route::get("stock-adjustment/verify", "StockAdjustmentController@getVerify")->name('stock-adjustment.verify');
Route::resource("stock-adjustment", "StockAdjustmentController");
Route::match(['GET', 'POST'], "lcd_inventory/print_barcode", "LcdInventoryController@print_barcode")->name('lcd_inventory.print_barcode');
Route::get("barcode", "LcdInventoryController@barcode")->name('lcd_inventory.barcode');
Route::get("barcode_generator", "LcdInventoryController@barcode_generator")->name('lcd_inventory.barcode_generator');
Route::get("issue_lcd", "LcdInventoryController@issue_lcd")->name('lcd_inventory.issue_lcd');
Route::get("broken_lcd", "LcdInventoryController@broken_lcd")->name('lcd_inventory.broken_lcd');
Route::get("check_barcode_is_exist", "LcdInventoryController@check_barcode_is_exist")->name('lcd_inventory.check_barcode_is_exist');
Route::get("lcd_profile", "LcdInventoryController@lcd_profile")->name('lcd_inventory.lcd_profile');
Route::get("phone_release_by_tester", "LcdInventoryController@phone_release_by_tester")->name('lcd_inventory.phone_release_by_tester');
Route::get("release_phone_for_refurbisher", "TestingController@release_phone_for_refurbisher")->name('testing.release_phone_for_refurbisher');
Route::get("phone_profile", "LcdInventoryController@phone_profile")->name('lcd_inventory.phone_profile');
Route::get("return_to_admin", "LcdInventoryController@return_to_admin")->name('lcd_inventory.return_to_admin');
Route::get("lcd_warehouse", "LcdInventoryController@lcd_warehouse")->name('lcd_inventory.lcd_warehouse');
Route::match(['GET', 'POST'], "attach_imei_with_lcd", "LcdInventoryController@attach_imei_with_lcd")->name('lcd_inventory.attach_imei_with_lcd');
Route::resource("lcd_inventory", "LcdInventoryController");

//search
//Route::match(['GET', 'POST'], "search", "SearchController@index")->name('search');

// reports
Route::match(['GET', 'POST'], "report/lot", "ReportController@lot")->name('report.lot');
Route::match(['GET', 'POST'], "release_by_tester", "WareHouseController@release_by_tester")->name('warehouse.release_by_tester');
Route::match(['GET', 'POST'],"report/asin", "ReportController@asin")->name('report.asin');
Route::match(['GET', 'POST'],"report/model_summary", "ReportController@ModelSummary")->name('report.model_summary');
Route::match(['GET', 'POST'],"report/model_summary_2", "ReportController@ModelSummary_2")->name('report.model_summary_2');
Route::match(['GET', 'POST'],"report/model_sales", "ReportController@modelSales")->name('report.model_sales');
Route::match(['GET', 'POST'],"report/tester", "ReportController@Tester")->name('report.tester');
Route::match(['GET', 'POST'],"report/dispatch", "ReportController@reportDispatch")->name('report.dispatch');
Route::get("report/dispatch/export", "ReportController@reportDispatchExport")->name('report.dispatch.export');
Route::get("report/tester/export", "ReportController@reportTesterExport")->name('report.tester.export');
Route::get("ExportDispatchToDay", "ReportController@ExportDispatchToDay")->name('ExportDispatchToDay');
Route::get("ExportRedFlag", "ReportController@ExportRedFlag")->name('ExportRedFlag');
Route::get("ExportRedFlag", "ReportController@ExportRedFlag")->name('ExportRedFlag');
Route::get("attachIMEIWithLCD", "ReportController@attachIMEIWithLCD")->name('report.attachIMEIWithLCD');
Route::get("brokenListReport", "ReportController@brokenListReport")->name('report.brokenListReport');
Route::get("lcdInventoryReport", "ReportController@lcdInventoryReport")->name('report.lcdInventoryReport');
Route::match(['GET' , 'POST'],"refurbisherReport", "ReportController@refurbisherReport")->name('report.refurbisherReport');
Route::match(['GET' , 'POST'],"refurbisherLcdReport", "ReportController@refurbisherLcdReport")->name('report.refurbisherLcdReport');

//general routsget_asin_by_storage_rest
Route::get('lot_by_lot_id/{lot_id?}','GeneralController@getLotByLotId')->name('lot_by_lot_id');
Route::get('imei_match_with_tracking_id/{track_id?}','GeneralController@checkIMEIMatchWithTrackingId')->name('imei_match_with_tracking_id');
Route::get('lot_by_lot_brand/{lot_id?}','GeneralController@getLotByLotIdBrand')->name('lot_by_lot_brand');
Route::get('lot_by_lot_model/{lot_id?}','GeneralController@getLotByLotIdModel')->name('lot_by_lot_model');
Route::get('get_storage_by_color/{lot_id?}','GeneralController@getStorageByColor')->name('get_storage_by_color');
Route::get('get_asin_by_storage/{lot_id?}','GeneralController@getAsinByStorage')->name('get_asin_by_storage');
Route::get('get_asin_by_storage_rest/{lot_id?}','GeneralController@getAsinByStorageRest')->name('get_asin_by_storage_rest');
Route::get('get_asin_by_storage_qty/{lot_id?}','GeneralController@getAsinByStorageQty')->name('get_asin_by_storage_qty');
Route::get('update_asin_quantity/{lot_id?}','GeneralController@updateAsinQuantity')->name('update_asin_quantity');
Route::get('get_asin_quantity_by_asin/{lot_id?}','GeneralController@getAsinQuantityByAsin')->name('get_asin_quantity_by_asin');
Route::get('lot_by_imei/{imei?}','GeneralController@getLotByImei')->name('lot_by_imei');
Route::get('lot_by_imeiTest/{imei?}','GeneralController@getLotByImeiTest')->name('lot_by_imeiTest');
Route::get('lot_by_imei_for_dispatch/{imei?}','GeneralController@getLotByImeiForDispatch')->name('lot_by_imei_for_dispatch');
Route::get('change_category','GeneralController@getChangeCategory')->name('change_category');
Route::get('inventory/delete/by/imei','GeneralController@inventoryDeleteByImei')->name('inventory.delete.by_imei');
Route::get('update/bought_qty/{id?}','GeneralController@updateBoughtQty')->name('update.bought_qty');
Route::get('revert_dispatch_by_imei','GeneralController@revert_dispatch_by_imei')->name('revert_dispatch_by_imei');
Route::get('color_by_brand_plus_model','GeneralController@color_by_brand_plus_model')->name('color_by_brand_plus_model');
Route::get('storage_by_brand_plus_model_color','GeneralController@storage_by_brand_plus_model_color')->name('storage_by_brand_plus_model_color');
Route::get('lot_by_brand_plus_model_color_storage','GeneralController@lot_by_brand_plus_model_color_storage')->name('lot_by_brand_plus_model_color_storage');
Route::get('lot_asin_by_brand_plus_model_color_storage','GeneralController@lot_asin_by_brand_plus_model_color_storage')->name('lot_asin_by_brand_plus_model_color_storage');
Route::get('get_asin_by__','GeneralController@get_asin_by__')->name('get_asin_by__');
Route::get('get_imei_category','GeneralController@get_imei_category')->name('get_imei_category');
Route::get('get_models_for_lots/{brand_name?}','GeneralController@getModelsByBrand')->name('get_models_for_lots');
//for reports only
Route::get('model_by_brand','GeneralController@getModelByBrand')->name('model_by_brand');
Route::get('lot_by_brand_plus_model','GeneralController@getLotByBrandPlusModel')->name('lot_by_brand_plus_model');
Route::get('network_storage_color_cat_by_brand','GeneralController@network_storage_color_cat_by_brand')->name('network_storage_color_cat_by_brand');


