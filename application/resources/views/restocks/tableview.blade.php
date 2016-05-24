<section class="content-header">
    <h1>
        Restocks({{$allRestocks->total()}})
    </h1>

</section>
<hr/>
<table class="table table-paper table-condensed table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>{!!HTML::sort('RestockController@index','products.productName','Product Name','restockeditems')!!}</th>
        <th>{!!HTML::sort('RestockController@index','restocks.unitCost','Unit Cost','restockeditems')!!}
            ({!!HTML::sort('RestockController@index','itemCost','Item Cost','restockeditems')!!})
        </th>
        <th>{!!HTML::sort('RestockController@index','amount','Amount','restockeditems')!!}</th>

        <th>{!!HTML::sort('RestockController@index','suppliers.supplierName','Supplied By','restockeditems')!!}</th>
        <th>{!!HTML::sort('RestockController@index','restocks.created_at','Received on','restockeditems')!!}</th>
        @include('restocks.custom.tableheader')
        <th>Docs</th>
        <th>Actions</th>

    </tr>
    </thead>
    <tbody>

    <?php $i = 1; ?>
    @foreach ($allRestocks as $restock)
        <tr class="">
            <th scope="row">{{$i}}</th>
            @if($restock->product)
                <td>{{ucwords($restock->present()->productName)}} </td>
            @else
                <td>-Deleted Product-</td>
            @endif

            <td>{{$restock->present()->unitCost}} ({{$restock->present()->itemCost}})</td>
            <td class="text-center"><b>{{doubleval($restock->amount)}}</b></td>
            <td>{{$restock->present()->supplierName}}</td>

            @include('restocks.custom.tablefields')
            <td>{{Carbon::parse($restock->created_at)->format('d/m/Y')}} </td>
            <td class="text-center">{!!$restock->present()->hasDownload!!}</td>
            <td class="text-center">
                @if(isset($restore))
                    <a href="{{action('RestockController@restore', $restock->id)}}" class="btn btn-flat bg-purple"><i
                                class="fa fa-undo"></i></a>
                @else
                    <div aria-label="Actions" role="group" class="btn-group">
                        <div  class="open-popup-link btn btn-flat bg-red delete-button"
                           data-url="{{action('RestockController@destroy', $restock->id)}}"><i
                                    class="fa fa-remove"></i></div>
                        <a class="btn btn-flat bg-blue" href="{{action('RestockController@edit', $restock->id)}}"> <i
                                    class="   fa fa-edit"></i></a>
                    </div>
                @endif

            </td>
            <?php $i++; ?>
        </tr>
    @endforeach

    </tbody>
</table>
<hr/>
<div class="text-center">
    {!!$allRestocks->appends($sort)->render()!!}
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