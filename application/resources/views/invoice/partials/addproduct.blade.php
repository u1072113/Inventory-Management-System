<div class="row">
    <div class="col-md-6">

        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Add Product That is under Stock Control
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group{!! $errors->has('productName') ? ' has-error' : '' !!}">
                    {!! Form::label('productName', 'Product Name') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                        {!! Form::select('productName',$products, null, ['class' => 'form-control',
                        'id'=>'productName']) !!}
                    </div>
                    {!! $errors->first('productName', '<p class="help-block">:message</p>') !!}
                </div>


            </div>
            <div class="panel-footer">
                <div class="btn btn-flat bg-green btn-block" id="addOrder">Add to Order</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Customer Name
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group{!! $errors->has('customerName') ? ' has-error' : '' !!}">
                    {!! Form::label('customerName', 'Customer Name') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        {!! Form::select('customerName',array(), null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('customerName', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('currency') ? ' has-error' : '' !!}">
                    {!! Form::label('currency', 'Currency') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::select('currency',array(), null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('currency', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>
    </div>
</div>