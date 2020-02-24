@extends('layouts.customer.app')

@section("title")
    Testing | IMEI Problem
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
                    <h3 class="m-subheader__title ">Testing | IMEI Problem</h3>
                </div>
            </div>
        </div>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <input type="hidden" id="showmessage" value="{{session('message')}}">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <label for="usr">IMEI </label>
                                <input type="text" class="form-control" autofocus name="justimei" id="" onchange="" value="{{$testing_ids[(count($testing_ids)-1)]->imei}}" disabled required>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <label for="usr">Model</label>
                                <input type="text" class="form-control" autofocus name="justmodel" id="" onchange="" value="{{$testing_ids[(count($testing_ids)-1)]->model}}" disabled required>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <label for="usr">Old Inventory Category</label>
                                <input type="text" class="form-control" autofocus name="justoldcat" id="" onchange="" value="{{$testing_ids[0]->old_cat}}" disabled required>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <div class="form-group margin-0">
                                <label for="usr">New Category</label>
                                <input type="text" class="form-control" autofocus name="justnewcat" id="" onchange="" value="{{$testing_ids[(count($testing_ids)-1)]->new_cat}}" disabled required>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <hr>
                </div>

                <?php
                $t_c = count($testing_ids);
                $total = $t_c;
                $first = 1;
                function addOrdinalNumberSuffix($num) {
                    if (!in_array(($num % 100),array(11,12,13))){
                        switch ($num % 10) {
                            // Handle 1st, 2nd, 3rd
                            case 1:  return $num.'st';
                            case 2:  return $num.'nd';
                            case 3:  return $num.'rd';
                        }
                    }
                    return $num.'th';
                }
                ?>
                @foreach ($testing_ids as $testing)
                    <?php
                    $test_date = date('M-d-Y', strtotime($testing->created_at));
                    $problems = App\Problems::where('testing_id', '=', $testing->id)->get();
                    ?>
                    <div class="m-portlet__body">
                        <hr>
                        <h3 style="text-align: center; color: #a92222">{{addOrdinalNumberSuffix($t_c)}} Time Testing</h3>
                        <hr>
                        <form action="{{URL::to('testing/' . $testing->id)}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                @if ($data != 0)
                                    @foreach($data as $kk => $vv)
                                        @if($testing->id == $vv->testing_id)
                                            <div class="col-md-3">
                                                <div class="form-group margin-0">
                                                    <label >Returned By</label>
                                                    <b class="form-control m--font-bold">{{$vv->name}}</b>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group margin-0">
                                                    <label class="">Returning Reason</label>
                                                    <b class="form-control m--font-bold">{{$vv->message}}</b>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group margin-0">
                                                    <label class="">Returned Category</label>
                                                    <b class="form-control m--font-bold">{{$testing->old_cat}}</b>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group margin-0">
                                            <label >Returned Date</label>
                                            <b class="form-control m--font-bold">{{date('M-d-Y', strtotime($vv->created_at))}}</b>
                                        </div>
                                    </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group margin-0">
                                        <input type="hidden" class="form-control" autofocus name="imei" id="imei_id_val" onchange="getLotByimei()" value="{{$testing->imei}}" disabled required>
                                    </div>
                                </div>
                            </div>
                            <?php $count = 0 ?>
                            <div class="row add_inventory">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <?php $count = 0 ?>
                                            @foreach($problems as $key=>$value)
                                                @if ($value->status == 0)
                                                    <?php $count++ ?>
                                                    <td style="text-align: center">
                                                        <label>
                                                            <input type="checkbox" name="{{$value->problem}}" class="form-check-input"
                                                                      value="1-Charger Port">
                                                            {{$value->problem_name}}
                                                        </label>
                                                    </td>
                                                @endif
                                            @endforeach
                                            {{--@if ($count == 0)
                                                <td>
                                                    <h3 class="text-success">All problems solved</h3>
                                                </td>
                                                    @if ($t_c + 1 > $total)
                                                <td> <a class="btn btn-warning"
                                                        href="{{route('testing.test_again',['id'=>$testing->id])}}" role="button">Test Again</a></td>
                                                        @endif
                                            @endif--}}
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if ($count != 0)
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" name="category" required>
                                                <option value="" selected>select category</option>
                                                @foreach(App\Category::all() as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary"style="width: 100%">Save</button>
                                    </div>
                                </div>
                            @endif
                            @if(($total < 2 || $t_c + 1 > $total) && $count == 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <h3 style="color: Green">Problems Timeline
                                                    <img src="https://img.icons8.com/cute-clipart/64/000000/double-tick.png">&emsp;&emsp;&emsp;
                                                    Date: {{$test_date}} &emsp;&emsp;&emsp;
                                                @if ($t_c + 1 > $total)
                                                    <a class="btn btn-warning"
                                                            href="{{route('testing.test_again',['id'=>$testing->id])}}" role="button">Test Again</a>
                                                @endif
                                                </h3>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                @foreach($problems as $key=>$value)
                                                    @if ($value->status == 1)
                                                        <td style="text-align: center">
                                                            <label>{{$value->problem_name}} </label>
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($t_c != $total)
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                                <h3 style="color: Green">Problems Timeline
                                                    <img src="https://img.icons8.com/cute-clipart/64/000000/double-tick.png">&emsp;&emsp;
                                                    Date: {{$test_date}}</h3>&emsp;&emsp;&emsp;
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            @foreach($problems as $key=>$value)
                                                @if ($value->status == 1)
                                                    <td style="text-align: center">
                                                        <label>{{$value->problem_name}} </label>
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            <hr>
                        </form>

                    </div>
                        <?php $t_c -= 1; ?>
                @endforeach
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

        $( document ).ready(function() {
            if ($('#showmessage').val() != ''){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Record has been added for testing',
                    showConfirmButton: true,
                    imageUrl: 'https://img.icons8.com/doodle/48/000000/checkmark.png',
                    imageWidth: 80,
                    imageHeight: 80,
                    imageAlt: 'success'
                })
            }
        });
    </script>
@endpush