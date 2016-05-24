<section class="content-header">
    <h1>
         {!! Helper::translateAndShorten('Suppliers','viewsupplier',15)!!}({{$suppliers->total()}})
        <small>{{$message}}</small>
    </h1>

</section>
<hr/>
<table class="table table-paper table-condensed table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>{!!HTML::sort('SupplierController@index','suppliers.supplierName','Supplier Name','viewsupplier')!!} ({!!HTML::sort('SupplierController@index','restockscount','restocks','viewsupplier')!!})
        </th>
        <th>{!!HTML::sort('SupplierController@index','suppliers.email','Email','viewsupplier')!!}</th>
        <th>{!!HTML::sort('SupplierController@index','suppliers.phone','Phone','viewsupplier')!!}</th>
        @include('suppliers.custom.tableheader')
        <th>{!!HTML::sort('SupplierController@index','restockssum','Total Amount','viewsupplier')!!}</th>
        <th>Edit</th>

    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach ($suppliers as $supplier)
    
        <tr class="">
            <th scope="row">{{$i}}</th>
            <td>{{ucwords($supplier->supplierName)}} ({{$supplier->restockscount}})</td>
            <td>{{$supplier->email}} </td>
            <td>{{$supplier->phone}}</td>
            @include('suppliers.custom.tablefields')
            <td>{{number_format($supplier->restockssum,2,'.',',')}}</td>
            <td class="text-center">
                @if(isset($restore))
                    <a href="{{action('SupplierController@restore', $supplier->id)}}" class="btn btn-flat bg-purple"><i
                                class="fa fa-undo"></i></a>
                @else
                    <div aria-label="Actions" role="group" class="btn-group">
                        <div class="open-popup-link btn btn-flat bg-red delete-button"
                           data-url="{{action('SupplierController@destroy', $supplier->id)}}"><i
                                    class="fa fa-remove"></i></div>
                        <a class="btn btn-flat bg-blue" href="{{action('SupplierController@edit', $supplier->id)}}"> <i
                                    class="   fa fa-edit"></i></a>
                    </div>
                @endif
            </td>
            {!! Form::close() !!}
            </td>
            <?php $i++; ?>
        </tr>
    @endforeach

    </tbody>
</table>
<hr/>
<div class="text-center">
    {!!$suppliers->appends($sort)->render()!!}
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