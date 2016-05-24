@extends('layouts.master')

@section('title')
    Purchase Orders
@endsection


@section('content')
    <section class="content-header">
        <h1>
            Your Purchase Orders ({{$purchaseOrders->count()}})
            <small>Your Purchase orders recent first</small>
        </h1>
    </section>
    <hr/>
    <div class="text-center">

        <table class="table table-paper table-bordered">

            <tbody>
            <tr>
                <td class="danger">Undelivered <span class="pull-right badge bg-blue">{{$undeliveredCount}}</span><a
                            href="{{action('PurchaseOrderController@index',array('status'=>'undelivered'))}}"><i
                                class="fa fa-filter"></i></a></td>
                <td class="danger">Late Delivery <span class="pull-right badge bg-blue">{{$lateDeliveryCount}}</span><a
                            href="{{action('PurchaseOrderController@index',array('status'=>'latedelivery'))}}"><i
                                class="fa fa-filter"></i></a></td>
                <td class="success">Delivered <span class="pull-right badge bg-blue">{{$deliveredCount}}</span> <a
                            href="{{action('PurchaseOrderController@index',array('status'=>'delivered'))}}"><i
                                class="fa fa-filter"></i></a></td>
                <td class="info">Partial Delivery <span class="pull-right badge bg-blue">{{$partdeliveredCount}}</span>
                    <a href="{{action('PurchaseOrderController@index',array('status'=>'partdelivery'))}}"><i
                                class="fa fa-filter"></i></a></td>
                <td class="warning">Awaiting Approval <span
                            class="pull-right badge bg-blue">{{$waitingApprovalCount}}</span>
                    <a href="{{action('PurchaseOrderController@index',array('status'=>'waitingApproval'))}}"><i
                                class="fa fa-filter"></i></a></td>
            </tr>

            </tbody>
        </table>
    </div>
    <hr/>

    <table class="table table-paper table-condensed table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('orders.Supplier') }}</th>
            <th>{!!HTML::sort('PurchaseOrderController@index','polDeliverBy','Deliver By','orders')!!} <i class="fa fa-info-circle pull-right" data-container="body" data-toggle="popover"
                              title="Info" data-content="Hover on this column to get additional info"
                              trigger="hover"></i></th>
            <th>{!!HTML::sort('PurchaseOrderController@index','lpoNumber','Lpo','orders')!!}</th>
            <th>{!!HTML::sort('PurchaseOrderController@index','polTermsOfPayment','Payment','orders')!!}</th>
            <th>{!!HTML::sort('PurchaseOrderController@index','lpoStatus','Status','orders')!!}</th>
            <th>{!!HTML::sort('PurchaseOrderController@index','lpoDate','LPO Date','orders')!!} <i class="fa fa-info-circle pull-right " data-container="body" data-toggle="popover"
                            title="Info" data-content="Hover on this column to get additional info" trigger="hover"></i>
            </th>
            <th>{{ trans('orders.Delivered') }}</th>
            <th>Actions <i class="fa fa-info-circle pull-right " data-container="body" data-toggle="popover"
                           title="Info" data-content="Hover on this column to get additional info" trigger="hover"></i>
            </th>

        </tr>
        </thead>
        <tbody>

        <?php $i = 1; ?>
        @foreach ($purchaseOrders as $order)
            @if($order->orders->sum('poQty') == $order->orders->sum('delivered'))
                <tr class="success">
            @elseif($order->orders->sum('delivered') < $order->orders->sum('poQty') and $order->orders->sum('delivered') > 0)
                <tr class="info">
            @elseif($order->lpoStatus == "Awaiting Approval")
                <tr class="warning">
            @else
                <tr class="danger">
                    @endif

                    <th class="" scope="row">{{$i}} {!! $order->present()->favourite()!!}</th>
                    <td data-container="body" data-toggle="popover"
                        title="Info" data-content="{!!$order->present()->supplierDetails!!}"
                        trigger="hover">{{str_limit(ucwords($order->supplier->supplierName),25)}}</td>

                    <td data-container="body" data-toggle="popover" title="Delivery Terms"
                        data-content="{{$order->present()->deliveryPopOver}}"
                        trigger="hover">{{$order->present()->delivery}} </td>
                    <td>{{$order->lpoNumber}}</td>
                    <td>{!!$order->present()->totalCash!!}</td>
                    <td>{{ucfirst($order->lpoStatus)}}</td>
                    <td data-container="body" data-toggle="popover" title="Delivery Terms"
                        data-content="{{$order->present()->createdPopOver}}"
                        trigger="hover">{{$order->present()->created}} </td>
                    <th>{{$order->present()->delivered}}</th>
                    <td class="text-center">
                        <div aria-label="Actions" role="group" class="btn-group">
                            @if(isset($restore))
                                <a href="{{action('PurchaseOrderController@restore', array('id'=>$order->id))}}"
                                   class="btn btn-warning"><i
                                            class="fa fa-undo"></i></a>
                            @else

                                @if($order->lpoStatus == "approved")
                                    @if($order->present()->fullDelivery == false)
                                        <a class="btn btn-flat bg-purple"
                                           href="{{action('PurchaseOrderController@getRestockFromPurchaseOrder', $order->id)}}"
                                           data-container="body" data-toggle="popover" title="Delivery Terms"
                                           data-content="Restock From LPO" trigger="hover"
                                                > <i
                                                    class="   fa fa-recycle"></i></a>
                                    @endif
                                @endif
                                <a class="btn btn-flat bg-green" target="_blank"
                                   href="{{ url('lpos/'.$order->lpoNumber . '-' . str_slug($order->supplier->supplierName) . '.pdf')}}"
                                   data-container="body" data-toggle="popover" title="Delivery Terms"
                                   data-content="Print From PDF" trigger="hover"
                                        > <i
                                            class="   fa fa-file-pdf-o"></i></a>
                                <a class="btn btn-flat bg-purple" target="_blank"
                                   href="{{ url('lpos/'.$order->lpoNumber . '-' . str_slug($order->supplier->supplierName) . '.xlsx')}}"
                                   data-container="body" data-toggle="popover" title="Delivery Terms"
                                   data-content="Print From Excel" trigger="hover"
                                        > <i
                                            class="   fa fa-file-excel-o"></i></a>
                                @if($order->lpoStatus != "approved")


                                    <a class="btn btn-info"
                                       href="{{action('PurchaseOrderController@edit', $order->id)}}"
                                       data-container="body" data-toggle="popover" title="Delivery Terms"
                                       data-content="Edit  LPO" trigger="hover"
                                            > <i
                                                class="   fa fa-edit"></i></a>
                                    <div class=" btn btn-flat bg-red delete-button"
                                         data-url="{{action('PurchaseOrderController@destroy', $order->id)}}"><i
                                                class="fa fa-remove"></i></div>
                                @endif



                            @endif

                        </div>
                    </td>

                    <?php $i++; ?>
                </tr>
                @endforeach

        </tbody>
    </table>
    <div class="text-center"><?php echo $purchaseOrders->appends($sort)->render(); ?></div>
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
@endsection

@section('js')


    $('[data-toggle="popover"]').popover({
    trigger:'hover',
    placement:'top'
    });
@endsection

