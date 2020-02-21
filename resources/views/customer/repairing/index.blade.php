@extends('layouts.customer.app')

@section("title")
    Repairing
@endsection
@push("css")
@endpush
@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title ">Repairing</h3>
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
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">All

                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="{{route('repairing.create')}}" class="btn btn-accent m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                                    <span>
													<i class="la la-plus"></i>
													<span>New Repairing</span>
												</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">

                    <!--begin: Datatable -->
                    <p><b id="replacedWithChecked"></b></p>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Network</th>
                            <th>Storage</th>
                            <th>Color</th>
                            <th>Imei</th>
                            <th>Category</th>
                            <th>Repaired By</th>
                            <th>Problems_solved</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            @if ($product->inventory && $product->user->name == auth()->user()->name)
                                <tr>
                                    <td>{{$product->inventory->lot->brand->name}}</td>
                                    <td>{{$product->inventory->lot->model}}</td>
                                    <td>{{$product->inventory->lot->network->name}}</td>
                                    <td>{{$product->inventory->lot->storage->name}}</td>
                                    <td>{{$product->inventory->lot->color}}</td>
                                    <td>{{$product->inventory->imei}}</td>
                                    <td>{{$product->inventory->category->name}}</td>
                                    <td>{{$product->user->name}}</td>
                                    <td>
                                        @foreach ($product->problems as $problem)
                                            {{$problem->problem_name}},
                                        @endforeach

                                    </td>
                                    <td title="{{$product->created_at}}">{{ date('M-d-Y', strtotime($product->created_at))}}</td>

                                </tr>
                            @endif

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>

    </div>
@stop
@push('scripts')
    <script>

    </script>
@endpush