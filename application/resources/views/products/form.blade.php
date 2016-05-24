<div class="panel panel-default cls-panel ">
    <div class="panel-heading ">
        <h3 class="panel-title">
            {!! \App\Helper::translateAndShorten('Add Product','addproduct',50)!!}
        </h3>
    </div>
    @if(isset($product))
        {!! Form::model($product, ['action' => ['ProductController@update', $product->id], 'method' =>
        'patch'])
        !!}
    @else
        {!! Form::open(array('action' => 'ProductController@store', 'files'=>false,'class'=>'')) !!}
    @endif
    <div class="panel-body">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('productName') ? ' has-error' : '' !!}">
                    {!! Form::label('productName',  trans('addproduct.Product Name') ) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                        {!! Form::text('productName', null, ['class' => 'form-control products', 'autocomplete'=>'off']) !!}
                    </div>
                    {!! $errors->first('productName', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('productSerial') ? ' has-error' : '' !!}">
                    {!! Form::label('productSerial', trans('addproduct.Product Serial Number')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        {!! Form::text('productSerial', null, ['class' => 'form-control serial']) !!}
                    </div>
                    {!! $errors->first('productSerial', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('amount') ? ' has-error' : '' !!}">
                    {!! Form::label('amount', trans('addproduct.Initial Amount of Item in Stock')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                        {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('reorderAmount') ? ' has-error' : '' !!}">
                    {!! Form::label('reorderAmount', trans('addproduct.Reorder Stock Amount')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-sort-numeric-desc"></i></span>
                        {!! Form::text('reorderAmount', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('reorderAmount', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('location') ? ' has-error' : '' !!}">
                    {!! Form::label('location', trans('addproduct.Product Location in Store')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                        {!! Form::text('location', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('expirationDate') ? ' has-error' : '' !!}">
                    {!! Form::label('expirationDate', trans('addproduct.Expiration Date')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('expirationDate', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('expirationDate', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                @include('products.custom.inputfields')
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('barcode') ? ' has-error' : '' !!}">
                    {!! Form::label('barcode', trans('addproduct.Barcode')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        {!! Form::text('barcode', null, ['class' => 'form-control','placeholder'=>'Text for Barcode'])
                        !!}
                    </div>
                    {!! $errors->first('barcode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('qrcode') ? ' has-error' : '' !!}">
                    {!! Form::label('qrcode', trans('addproduct.QrCode')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-qrcode"></i></span>
                        {!! Form::text('qrcode', null, ['class' => 'form-control','placeholder'=>'Text for Barcode'])
                        !!}
                    </div>
                    {!! $errors->first('qrcode', '<p class="help-block">:message</p>') !!}
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('unitCost') ? ' has-error' : '' !!}">
                    {!! Form::label('unitCost', trans('addproduct.Unit Cost (This will be used to calculate amount each department uses)')) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('unitCost', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('unitCost', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group{!! $errors->has('categoryId') ? ' has-error' : '' !!}">
                    {!! Form::label('categoryId', 'Category') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                        {!! Form::select('categoryId',array(), null, ['class' => 'form-control catSelect']) !!}
                        <span v-on:click="openModal" class="input-group-addon bg-green"><i
                                    class="fa fa-plus"></i></span>
                    </div>
                    {!! $errors->first('categoryId', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('productSpecification') ? ' has-error' : '' !!}">
                    {!! Form::label('productSpecification', 'Product Specification') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                        {!! Form::text('productSpecification', null, ['class' => 'form-control','placeholder'=>'Product Specification']) !!}

                    </div>
                    {!! $errors->first('productSpecification', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{!! $errors->has('buyingPrice') ? ' has-error' : '' !!}">
                    {!! Form::label('buyingPrice', 'Buying Price Ammount Without Profit') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('buyingPrice', null, ['class' => 'form-control','placeholder'=>'Buying Price']) !!}

                    </div>
                    {!! $errors->first('buyingPrice', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group{!! $errors->has('image') ? ' has-error' : '' !!}">
                    <div class="text-center"> {!! Form::label('image', trans('addproduct.Upload Image Of Product')) !!}</div>
                    <div class="input-group col-md-12">
                        <div class="dropzone col-md-12" id="image"></div>
                    </div>
                    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="productImage"/>
        <input type="hidden" name="categoryName" v-model="categoryNamePost"/>
    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-flat bg-green btn-block"><i class="fa fa-save"></i> Add Product</button>
    </div>
    {!! Form::close() !!}
</div>
