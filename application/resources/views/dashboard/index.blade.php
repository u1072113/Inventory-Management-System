@extends('layouts.master')

@section('title')
    Dashboard
@endsection


@section('content')
    @include('dashboard.recap')
    @include('dashboard.dispatch')
    @include('dashboard.actions')


@endsection

@section('js')

    var data = JSON.parse('{!!$supplierAmountReport!!}');
    Morris.Donut({
    element: 'stats-container',
    data:data
    });

    var data = JSON.parse('{!! $dispatchTrend !!}');
    var monthData= JSON.parse('{!! $dispatchTrend !!}');
    new Morris.Bar({
    // ID of the element in which to draw the chart.
    element: 'stats-container2',
    data: data,
    xkey: 'day',
    ykeys: ['dispatchcount'],
    labels: ['Dispatches']
    });
@endsection