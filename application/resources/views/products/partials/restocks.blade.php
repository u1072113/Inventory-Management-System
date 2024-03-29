<div class="col-md-7">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">View Restocks</h3>

            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table table-paper table-condensed table-bordered">
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>Restock Date</th>
                        <th>Product Name</th>
                        <th>Restock Amount</th>
                        <th>Restock Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($product->restocks->take(5) as $restock)
                        <tr class="">
                            <td>{{ucwords($product->created_at)}}</td>
                            <td>{{ucwords($product->productName)}}</td>
                            <td>{{ucwords($restock->amount)}}</td>
                            <td>{{ucwords($restock->unitCost)}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->

        <!-- /.box-footer -->
    </div>
</div>

