<div class="panel panel-default cls-panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            {!! Helper::translateAndShorten('Restock Items','restock',100)!!}
        </h3>
    </div>
    @if(isset($restock))
        {!! Form::model($restock, ['action' => ['RestockController@update', $restock->id], 'method' => 'patch']) !!}
    @else
        {!! Form::open(array('action' => 'RestockController@store')) !!}
    @endif
    <div class="panel-body">
        <div class="form-group{!! $errors->has('productID') ? ' has-error' : '' !!}">
            {!! Form::label('productID',  trans('restock.Select Product To Restock') ) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                {!! Form::select('productID',$products, null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('productID', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{!! $errors->has('supplierID') ? ' has-error' : '' !!}">
            {!! Form::label('supplierID',trans('restock.Suplier Name')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                {!! Form::select('supplierID',$allSuppliers, null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('supplierID', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('itemCost') ? ' has-error' : '' !!}">
                    {!! Form::label('itemCost',trans('restock.Item Cost (Total cost for items)')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('itemCost', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('itemCost', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('unitCost') ? ' has-error' : '' !!}">
                    {!! Form::label('unitCost',trans('restock.Unit Cost (This will be used to calculate amount each department uses)') ) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('unitCost', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('unitCost', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="form-group{!! $errors->has('amount') ? ' has-error' : '' !!}">
            {!! Form::label('amount',trans('restock.Number of Items ( Pieces)')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
        </div>
        @include('restocks.custom.inputfields')
        <div class="form-group{!! $errors->has('remarks') ? ' has-error' : '' !!}">
            {!! Form::label('remarks',trans('restock.Remarks')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                {!! Form::text('remarks', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group{!! $errors->has('image') ? ' has-error' : '' !!}">
                    <div class="text-center"> {!! Form::label('image',  trans('restock.Upload zip of receipts') ) !!}</div>
                    <div class="input-group col-md-12">
                        <div class="dropzone col-md-12" id="image"></div>
                    </div>
                    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <input type="hidden" name="restockDocs" id="restockDocs"/>
    </div>
    <div class="panel-footer">
        <button class="btn btn-flat bg-green btn-block">Record Restock</button>
    </div>
    {!! Form::close() !!}
</div>

