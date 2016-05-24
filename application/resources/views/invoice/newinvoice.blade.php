@extends('layouts.master')

@section('title')

@endsection

@section('sidebar')

@endsection

@section('content')
    @if(isset($invoice))
        {!! Form::model($invoice, ['action' => ['InvoiceController@update', $invoice->id], 'method' =>
        'patch'])
        !!}
    @else
        {!! Form::open(array('action' => 'InvoiceController@store', 'files'=>false)) !!}
    @endif
    @include('invoice.partials.addproduct')
    <div class="panel panel-default cls-panel">
        <div class="panel-heading">
            <h3 class="panel-title">
                Add Item to Invoice
            </h3>
        </div>

        <div class="panel-body">
            <div id="tablecontent">

            </div>
            <input type="hidden" name="details" id="invoiceDetails"/>
        </div>
    </div>
    <button type="submit" class="btn btn-flat bg-green btn-block">Save Invoice</button>

    {!! Form::close() !!}



@endsection

@section('jquery')
    @include('invoice.partials.editablegrid')
@endsection