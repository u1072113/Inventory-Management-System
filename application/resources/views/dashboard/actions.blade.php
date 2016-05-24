<div class="row">
    <!-- Low Stock Items -->
    <div class="col-md-3">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{$lowStock}}</h3>

                <p> {!! \App\Helper::translateAndShorten('Out of stock Items','dashboard',20)!!}</p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="{{url('product/stock/warning')}}" class="small-box-footer"> {!! \App\Helper::translateAndShorten('More info','dashboard',20)!!} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
        <!-- Low Stock items -->

 
<!--Dispatches Per Month -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    {{number_format($dispatchCount)}}
                </h3>

                <p> {!! \App\Helper::translateAndShorten('Dispatched Products so far','dashboard',20)!!}</p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="{{url('dispatch')}}" class="small-box-footer"> {!! \App\Helper::translateAndShorten('View Dispatches','dashboard',20)!!} <i
                        class="fa fa-arrow-circle-right"></i></a>
        </div>

    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"> {!! \App\Helper::translateAndShorten('Ammount Used on Suppliers','dashboard',50)!!}</h3>

                <div class="box-tools pull-right">
                    <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                    <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <div class="stats-container" id="stats-container"></div>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <div class="col-md-4">
                        <div style="min-height: 280px" class="pad box-pane-right bg-green">
                            <div class="description-block margin-bottom">
                                <div data-color="#fff" class="sparkbar pad">
                                    <canvas style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"
                                            width="34" height="30"></canvas>
                                </div>
                                <h5 class="description-header">{{number_format($supplierCount)}}</h5>
                                <span class="description-text"> {!! \App\Helper::translateAndShorten('Suppliers','dashboard',20)!!}</span>
                            </div>
                            <!-- /.description-block -->
                            <div class="description-block margin-bottom">
                                <div data-color="#fff" class="sparkbar pad">
                                    <canvas style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"
                                            width="34" height="30"></canvas>
                                </div>
                                <h5 class="description-header">{{$restockCount}}</h5>
                                <span class="description-text"> {!! \App\Helper::translateAndShorten('Restocks','dashboard',20)!!}</span>
                            </div>
                            <!-- /.description-block -->
                            <div class="description-block">
                                <div data-color="#fff" class="sparkbar pad">
                                    <canvas style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"
                                            width="34" height="30"></canvas>
                                </div>
                                <h5 class="description-header">{{number_format($dispatchCount)}}</h5>
                                <span class="description-text"> {!! \App\Helper::translateAndShorten('Restocks','dashboard',20)!!}</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>

            <!-- /.footer -->
        </div>
    </div>
</div><!-- /.row -->
