@extends('layouts.master')
@section('title')
    Add New Department
@endsection

@section('content')
    @include('departments.form')
@endsection

@section('js')
    $('#budgetStartDate,#budgetEndDate').datepicker({
    format:"yyyy/mm/dd"
    });
@endsection