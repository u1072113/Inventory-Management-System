@extends('layouts.master')

@section('title')
Restock Item in inventory
@endsection



@section('content')
    @include('restocks.form')
@endsection


@section('js')
    $('select').select2({
    //Allow manually entered text in drop down.
    createSearchChoice: function (term, data) {
    if ($(data).filter(function () {
    return this.text.localeCompare(term) === 0;
    }).length === 0) {
    return {id: term, text: term};
    }
    },
    });

    Dropzone.options.image = {
    maxFiles: 1,
    url: "{!!url('restock/upload/docs')!!}",
    paramName: "file",
    dictDefaultMessage: "Upload your zip of receipts/invoices here",
    acceptedFiles: "image/*,application/zip,application/pdf",
    headers: {
    "X-CSRF-Token": $('input[name="_token"]').val()
    },
    uploadprogress: function (progress, bytesSent) {
    console.log(progress);
    },
    success:function(file,response){
    console.log(response.save_path);
    $('input[name="restockDocs"]').val(response.save_path);
    },
    maxfilesexceeded: function(file) {
    this.removeAllFiles();
    this.addFile(file);
    }
    };
@endsection