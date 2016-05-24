@extends('layouts.master')

@section('title')
    View Restock Logs
@endsection






@section('content')
    @include('restocks.tableview')
@endsection

@section('js')

            $('select').select2({
            allowClear: true,
            placeholder: "Please Select ",
            //Allow manually entered text in drop down.
            createSearchChoice: function (term, data) {
            if ($(data).filter(function () {
            return this.text.localeCompare(term) === 0;
            }).length === 0) {
            return {id: term, text: term};
            }
            },
            });
@endsection
