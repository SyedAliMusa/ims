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
                    <h3 class="m-subheader__title ">Testing | IMEI problem</h3>
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
                    <div class="row">
                        <div class="col-md-4">
                            <p>Old Inventory Category = <span class="text-danger"> {{$problem_record->category->name}}</span></p>
                            <p>Tested Category = <span class="text-success"> {{$problem_record->inventory->category->name}}</span></p>
                            <p>Tested By = <span class="text-primary"> {{$problem_record->user->name}}</span></p>
                        </div>
                    </div>
                    <hr>
                    <br><br>
                    <form action="{{URL::to('testing/' . $problem_record->id)}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group margin-0">
                                    <label for="usr">IMEI</label>
                                    <input type="text" class="form-control" autofocus name="imei" id="imei_id_val" onchange="getLotByimei()" value="{{$problem_record->inventory->imei}}" disabled required>
                                </div>
                            </div>
                        </div>
                        <?php $count = 0 ?>
                        <div class="row add_inventory">

                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

                                <div class="form-group margin-0">
                                    <label for="usr">Brand</label>
                                    <input type="text" class="form-control" id="brand" disabled value="{{$problem_record->inventory->lot->brand->name}}" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Model</label>
                                    <input type="text" class="form-control" id="network" disabled value="{{$problem_record->inventory->lot->model}}" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Network</label>
                                    <input type="text" class="form-control" id="network" disabled value="{{$problem_record->inventory->lot->network->name}}" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Storage</label>
                                    <input type="text" class="form-control" id="storage" disabled value="{{$problem_record->inventory->lot->storage->name}}" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Color</label>
                                    <input type="text" class="form-control" id="color" disabled value="{{$problem_record->inventory->lot->color}}" required>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group margin-0">
                                    <label for="pwd">Testing_Category</label>
                                    <input type="text" class="form-control" id="category" disabled value="{{$problem_record->inventory->category->name}}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <?php $count = 0 ?>
                                        @foreach($problems as $key=>$value)
                                            @if ($value->status == 0)
                                                <?php $count++ ?>
                                                <td><div class="form-group">
                                                        <div class="radio">
                                                            <label><input type="checkbox" name="{{$value->problem}}" class="form-check-input"
                                                                          value="1-Charger Port">
                                                                {{$value->problem_name}}</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                        @if ($count == 0)
                                            <td><h3 class="text-success">All problems solved</h3></td>
                                            <td> <a class="btn btn-warning"
                                                    href="{{route('testing.test_again',['id'=>$problem_record->id])}}" role="button">Test Again</a></td>

                                        @endif
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
                                            @foreach($categories as $item)
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
                        <hr>
                        <br>
                        <br>
                        <div class="row">
                            <h3>Problems Timeline</h3>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        @foreach($problems as $key=>$value)
                                            @if ($value->status == 1)
                                                <td><div class="form-group">
                                                        <div class="radio">
                                                            <label><input type="checkbox" name="{{$value->problem}}" class="form-check-input"
                                                                          value="1-Charger Port" checked>
                                                                {{$value->problem_name}}</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <br><br><br><br>

                    </form>



                    @foreach($timelines as $timeline)
                        @if ($timeline->id != $problem_record->id)
                            <div class="row">
                                <div class="col-md-4">
                                    <p>Old Inventory Category = <span class="text-danger"> {{$timeline->category->name}}</span></p>
                                    <p>Tested Category = <span class="text-success"> {{$timeline->inventory->category->name}}</span></p>
                                    <p>Tested By = <span class="text-primary"> {{$timeline->user->name}}</span></p>
                                </div>
                            </div>
                            <br><br>
                            <form action="#" method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group margin-0">
                                            <label for="usr">IMEI</label>
                                            <input type="text" class="form-control" autofocus name="imei" id="imei_id_val"  value="{{$timeline->inventory->imei}}" disabled required>
                                        </div>
                                    </div>
                                </div>
                                <?php $count = 0 ?>
                                <div class="row add_inventory">

                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

                                        <div class="form-group margin-0">
                                            <label for="usr">Brand</label>
                                            <input type="text" class="form-control" id="brand" disabled value="{{$timeline->inventory->lot->brand->name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group margin-0">
                                            <label for="pwd">Model</label>
                                            <input type="text" class="form-control" id="network" disabled value="{{$timeline->inventory->lot->model}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group margin-0">
                                            <label for="pwd">Network</label>
                                            <input type="text" class="form-control" id="network" disabled value="{{$timeline->inventory->lot->network->name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group margin-0">
                                            <label for="pwd">Storage</label>
                                            <input type="text" class="form-control" id="storage" disabled value="{{$timeline->inventory->lot->storage->name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group margin-0">
                                            <label for="pwd">Color</label>
                                            <input type="text" class="form-control" id="color" disabled value="{{$timeline->inventory->lot->color}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group margin-0">
                                            <label for="pwd">Testing_Category</label>
                                            <input type="text" class="form-control" id="category" disabled value="{{$timeline->inventory->category->name}}" required>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </form>
                        @endif
                    @endforeach
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


    </script>
@endpush