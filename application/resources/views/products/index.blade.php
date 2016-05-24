@extends('layouts.master')

@section('title')
    View All Items in inventory
@endsection


@section('content')
    @include('products.tableview')

    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Dispatch Log</h3>
        </div>
        <div class="box-body">
            <div class="stats-container" id="stats-container"></div>
        </div>
    </div>

@endsection


@section('jquery')
    <script>
        $(function () {





                    @if(isset($amountChart))
                        var data = JSON.parse('{!! $amountChart !!}');
            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'stats-container',
                data: data,
                xkey: 'productName',
                ykeys: ['amount', 'reorderAmount'],
                labels: ['Amount', 'reorderAmount']
            });
            @endif







        });
    </script>
@endsection


