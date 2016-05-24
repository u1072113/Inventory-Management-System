
<section class="content-header">
    <h1>
        {!! Helper::translateAndShorten('Products','stockitems',50)!!}({{$stockItems->total()}})
        <small>{{$message}}</small>
    </h1>

</section>
<hr/>
<table class="table table-paper table-condensed table-hover table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>{!!HTML::sort('ProductController@index','productName','Product Name','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','location','Location','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','categoryName','Category','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','productSerial','Serial','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','unitCost','Unit Cost','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','amount','Amnt','stockitems')!!}</th>
        <th>{!!HTML::sort('ProductController@index','reorderAmount','ReorderAmt','stockitems')!!}</th>
        @include('products.custom.tableheader')
        <th>Actions</th>

    </tr>
    </thead>
    <tbody>

    <?php $i = 1; ?>
    @foreach ($stockItems as $product)
        @if($product->amount <$product->reorderAmount or $product->amount == 0)
            <tr class="danger ">
        @else
            <tr class="">
                @endif

                <th scope="row">{{$i}}</th>
                <td>{{str_limit($product->present()->productName,20)}}
                </td>
                <td>{{$product->location}}</td>
                <td>{{$product->categoryName}}</td>
                <td>{{ str_limit($product->productSerial,6)}} </td>
                <td class="text-center">{{$product->present()->unitCost}} </td>
                <td class="text-center">
                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar  {{$product->present()->viewPercentage}}"
                             style="width: {{$product->present()->percentage}}"></div>
                    </div>
                    {{$product->present()->amount}}</td>
                <td class="text-center">{{$product->present()->reorderAmount}}</td>
                @include('products.custom.tablefields')
                <td class="text-center">
                    <div aria-label="Actions" role="group" class="btn-group">
                        @if(isset($restore))
                            <a href="{{action('ProductController@restore', $product->id)}}"
                               class="btn btn-flat bg-purple"><i
                                        class="fa fa-undo"></i></a>
                        @else
                            <div class="open-popup-link btn btn-flat bg-red delete-button"
                                 data-url="{{action('ProductController@destroy', $product->id)}}"><i
                                        class="fa fa-remove"></i></div>

                            <a class=" btn btn-flat bg-blue" href="{{action('ProductController@edit', $product->id)}}">
                                <i
                                        class="   fa fa-edit"></i></a>
                            <!--
                            <a class="btn btn-flat bg-yellow"
                               href="{{action('ProductController@show', $product->id)}}"><i class="fa fa-eye"></i></a>-->
                        @endif

                    </div>
                </td>
                <?php $i++; ?>
            </tr>
            @endforeach

    </tbody>
</table>
<hr/>
<div class="text-center"><?php echo $stockItems->appends($sort)->render(); ?></div>
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

