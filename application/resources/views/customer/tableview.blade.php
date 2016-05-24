<section class="content-header">
    <h1>
        Customers and Their Stats({{$customers->total()}})
    </h1>

</section>
<hr/>
<table class="table table-paper table-condensed table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>{!!HTML::sort('CustomerController@index','customerCode','Customer Code')!!}</th>
        <th>{!!HTML::sort('CustomerController@index','customerName','Customer Name')!!}</th>
        <th>{!!HTML::sort('CustomerController@index','contactPerson','Customer Contact Person')!!}</th>
        <th>{!!HTML::sort('CustomerController@index','creditLimit','Credit Limit')!!}</th>
        <th>{!!HTML::sort('CustomerController@index','created_at','Created At')!!}</th>
        <th>Updated At</th>
        <th>Actions</th>

    </tr>
    </thead>
    <tbody>

    <?php $i = 1; ?>
    @foreach ($customers as $customer)
        <tr class="">
            <th scope="row">{{$i}}</th>
            <td>{{ucwords($customer->customerCode)}}</td>
            <td>{{$customer->customerName}}</td>
            <td>{{$customer->contactPerson}} </td>
            <td>{{$customer->creditLimit}}</td>
            <td>{{Carbon::parse($customer->created_at)->format('d/m/Y')}} </td>
            <td>{{Carbon::parse($customer->updated_at)->format('d/m/Y')}} </td>
            <td>
                <div aria-label="Actions" role="group" class="btn-group">
                    @if(isset($restore))
                        <a href="{{action('CustomerController@restore', $customer->id)}}"
                           class="btn btn-flat bg-purple"><i
                                    class="fa fa-undo"></i></a>
                    @else
                        <div class="open-popup-link btn btn-flat bg-red delete-button"
                             data-url="{{action('CustomerController@destroy', $customer->id)}}"><i
                                    class="fa fa-remove"></i></div>

                        <a class=" btn btn-flat bg-blue" href="{{action('CustomerController@edit', $customer->id)}}"> <i
                                    class="   fa fa-edit"></i></a>
                        <a class="btn btn-flat bg-yellow"
                           href="{{action('CustomerController@show', $customer->id)}}"><i class="fa fa-eye"></i></a>
                    @endif
                </div>
            </td>
            <?php $i++; ?>
        </tr>
    @endforeach

    </tbody>
</table>
<hr/>
<div class="text-center"><?php echo $customers->appends($sort)->render(); ?></div>