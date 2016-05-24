@extends('layouts.master')

@section('title')
    Import Data
@endsection


@section('content')
    <div class="alert alert-danger text-center">
        To import data you need to have data in the format below please download the Data Transfer Workbench Excel Sheet
        here. <b>ProductName and Location Must be filled out </b>
        <br/><br/>
        Fields contained are
        @foreach($columns as $column)
            <b>  {{$column}},</b>

        @endforeach
        <br/>
        <a class="btn btn-primary text-center" href="{{url('/product/stock/import?download=true')}}">Download
            Workbench</a>
    </div>

    {!! Form::open(array('action' => 'ProductController@import', 'files'=>true)) !!}
    <div class="form-group{!! $errors->has('workbenchfile') ? ' has-error' : '' !!}">
        {!! Form::label('workbenchfile', 'Workbench File') !!}

        {!! Form::file('workbenchfile', ['class' => '']) !!}

        {!! $errors->first('workbenchfile', '<p class="help-block">:message</p>') !!}
    </div>
    <button type="submit" class="btn btn-primary">Import Data</button>
    {!! Form::close() !!}


@endsection

@section('js')

@endsection