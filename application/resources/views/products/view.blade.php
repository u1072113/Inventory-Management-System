@extends('layouts.master')

@section('title')
    View Product
@endsection



@section('content')
    <div class="row">
        <img src="{{url('barcodes/barcode/'.$product->barcodeFileName)}}"/>
        <img src="{{url('barcodes/qrcode/'.$product->qrcodeFileName)}}"/>
        <?php echo DNS1D::getBarcodePNGPath("hello3456", "C128", 2, 30);?>
        @include('products/partials/restocks')
    </div>

@endsection

@section('js')

@endsection