<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.8.0/dist/JsBarcode.all.min.js"></script>

    <!-- Bootstrap CSS -->
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--}}
    <style type="text/css" media="screen"></style>

    <style type="text/css" media="print"></style>

    <style>
        @media print {
            #printPageButton {
                display: none;
            }
        }
        @page {
            /*size: portrait !important;
            margin: 5cm 2cm;*/
        }

        /* @media print {
             h1 {page-break-after: always !important;}
             body{
                 text-align: center !important;
             }
         }*/
    </style>
    <style type="text/css">
        #middle {position: absolute; top: 50%; overflow: auto;} /* for explorer only*/
        #middle[id] {vertical-align: middle; width: 100%;}
        #inner {position: relative; /*top: -50%*/} /* for explorer only */
        /*#img{    position: inherit;*/
        /*top: 515px;*/
    </style>
</head>
<body {{--class="d-print-block" style="background-image: url('https://mobiledemand-barcode.azurewebsites.net/barcode/image?content=1231abc-&amp;size=100&amp;symbology=CODE_128&amp;format=png&amp;text=true');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;"--}}
>
{{--<button id="printPageButton" onclick="myFunction()">Print Barcode</button>--}}

<?php
if(isset($_GET['submit'])) {
    $band = \App\Brand::find($_GET['brand_id']);
//    print_r($band->name);die;
    $model = trim($_GET['model']);
    $cat = \App\Category::where('name','=', trim($_GET['category']))->first();
    $category = $cat->name;
    $type=$_GET['type'];
    $orientation=$_GET['orientation'];
    $size=$_GET['size'];
    $print=$_GET['print'];
    $quantity=$_GET['quantity'];

//    zabra 2844
}
?>

@for ($i = 1; $i <= $quantity; $i++)
    <?php
    $date=date_create(\Carbon\Carbon::now());
    //    $random_no = date_format($date,"his"). $i;
    $code = mt_rand(111111,999999);
    $random_no = $code;
    $is_barcode_exist =\App\DumyBarcode::where('barcode','=', $random_no)->first();
    if (!$is_barcode_exist){
        $lcd_id = \App\LcdInventory::insertGetId([
            'barcode' => $random_no,
            'brand_id' => $_GET['brand_id'],
            'modal' => $_GET['model'],
            'category_id' => $cat->id,
            'status' => '3', // released,
            'created_by' => auth()->user()->id
        ]);
        \App\LcdIssuedTo::insert([
            'lcd_inventory_id' => $lcd_id,
            'assigned_to' => 'LCD_Refurbished',
            'receiver_name' => 'Rainel',
            'status' => '3', // released,
            'assigned_by' => auth()->user()->id
        ]);
    }
    ?>
    @if (!$is_barcode_exist)
        <table style="width: 100%;">
            <tr style="text-align: center">
                <td>
                    <div style="margin-top: 5%;margin-left: -7px">
                        <!--<p style="margin-bottom: -2px; "><b> {{ $band->name }} {{ $model }} {{$category }}</b></p>-->
                        <p style="margin-bottom: -2px;font-size:14px; "><b> {{ $model }} {{$category }}</b></p>
                            <?php
                            echo DNS1D::getBarcodeSVG($random_no, "C39", 1, 25, '#2A3239');
                            ?>
                            <p style="margin-top: -2px;font-size:14px; "><b> {{ $random_no }} </b></p>
                    </div>
                </td>
            </tr>
        </table>
        <h1 style="page-break-after: always !important;"></h1>
    @endif
@endfor
<script>
    function myFunction() {
        window.print();
    }
</script>
</body>
</html>



