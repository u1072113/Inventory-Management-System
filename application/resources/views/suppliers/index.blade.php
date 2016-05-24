@extends('layouts.master')

@section('title')
    View All Suppliers
@endsection



@section('content')
    @include('suppliers.tableview')

    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Suppliers Log</h3>
        </div>
        <div class="box-body">
            <div class="stats-container" id="stats-container"></div>
        </div>
    </div>

@endsection
@section('js')

    @if(isset($supplierAmountReport))
        var data = JSON.parse('{!!$supplierAmountReport!!}');
        Morris.Donut({
        element: 'stats-container',
        data:data
        });
    @endif
@endsection

