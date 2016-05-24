@extends('layouts.master')

@section('title')
    Dispatch Items From Stock
@endsection

@section('report')
    <div aria-label="Actions" role="group" class="btn-group">
        <a href="{{url('/dispatch/stock/export?type=xlsx')}}" class="btn btn-warning"><i
                    class="fa fa-file-excel-o"></i> Excel</a>
        <a href="#email-popup" data-url="{{action('DispatchController@export')}}"
           class="email-popup-link btn btn-primary"
           data-url="#"><i
                    class="fa fa-envelope"></i> Email To</a>
        <a class="btn btn-info" href="{{url('/dispatch/stock/export?type=csv')}}"><i class="fa fa-file"></i> CSV</a>
    </div>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Dispatches({{$dispatchedItems->total()}})
        </h1>

    </section>
    <hr/>
    <table class="table table-paper table-condensed table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>{!!HTML::sort('DispatchController@index','products.productName','Stock Item','viewdispatcheditems')!!}</th>
            <th>{!!HTML::sort('DispatchController@index','dispatches.amount','Amount','viewdispatcheditems')!!}</th>
            <th>{!!HTML::sort('DispatchController@index','dispatches.categoryName','Category Name','viewdispatcheditems')!!}</th>
            <th>{!!HTML::sort('DispatchController@index','staff.name','Dispatched To','viewdispatcheditems')!!}</th>
            <th>{!!HTML::sort('DispatchController@index','dispatches.totalCost','Item Cost','viewdispatcheditems')!!}</th>
            @include('dispatches.custom.tableheader')
            <th>{!!HTML::sort('DispatchController@index','dispatches.created_at','Dispatched On','viewdispatcheditems')!!}</th>
            <th>Actions</th>

        </tr>
        </thead>
        <tbody>

        <?php $i = 1; ?>
        @foreach ($dispatchedItems as $dispatch)
            <tr class="">
                <th scope="row">{{$i}}</th>
                @if($dispatch->product)
                    <td>{{ucwords($dispatch->present()->productName)}}</td>
                @else
                    <td>-Deleted Product-</td>
                @endif

                <td class="text-center"><b>{{doubleval($dispatch->amount)}}</b></td>
                <td class="text-center"><b>{{$dispatch->categoryName}}</b></td>
                @if($dispatch->staff)
                    <td>{{$dispatch->staff->name}} </td>
                @else
                    <td>-Deleted User -</td>
                @endif
                <td>{{$dispatch->present()->totalCost}}</td>
                @include('dispatches.custom.tablefields')
                <td>{{$dispatch->created_at}} </td>
                <td class="text-center">
                    @if(isset($restore))
                        <a href="{{action('DispatchController@restore', $dispatch->id)}}"
                           class="btn btn-flat bg-purple"><i
                                    class="fa fa-undo"></i></a>
                    @else
                        <div aria-label="Actions" role="group" class="btn-group">
                            <div class="btn btn-flat bg-red delete-button"
                                 data-url="{{action('DispatchController@destroy', $dispatch->id)}}"><i
                                        class="fa fa-remove"></i></div>
                            <a class="btn btn btn-flat bg-blue"
                               href="{{action('DispatchController@edit', $dispatch->id)}}"> <i
                                        class="   fa fa-edit"></i></a>
                            @endif
                        </div>


                </td>
                {!! Form::close() !!}
                <?php $i++; ?>
            </tr>
        @endforeach

        </tbody>
    </table>
    <hr/>
    <div class="text-center">
        {!!$dispatchedItems->appends($sort)->render()!!}
    </div>
    <div class="text-center">
        <div class="btn-group" data-toggle="buttons">

            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(10)}} id="q156" name="quality[25]" value="10"/> 10
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(20)}} id="q157" name="quality[25]" value="20"/> 20
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(30)}} id="q158" name="quality[25]" value="30"/> 30
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(40)}} id="q159" name="quality[25]" value="40"/> 40
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(50)}} id="q160" name="quality[25]" value="50"/> 50
                Items Per Page
            </label>
        </div>
    </div>
    <hr/>

    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Dispatch Log</h3>
        </div>
        <div class="box-body">
            <div class="stats-container" id="stats-container"></div>
        </div>
    </div>
@endsection


@section('js')

    @if(isset($dispatchTrend))
        var data = JSON.parse('{!! $dispatchTrend !!}');
        var monthData= JSON.parse('{!! $dispatchTrend !!}');
        new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'stats-container',
        data: data,
        xkey: 'day',
        ykeys: ['dispatchcount'],
        labels: ['Dispatches']
        });
    @endif


@endsection



