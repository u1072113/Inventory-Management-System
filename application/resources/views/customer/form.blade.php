<section class="content-header">
    <h1>
        Create New Customer
        <small>Add New Customer</small>
    </h1>

</section>
<hr/>

@if(isset($customer))
    {!! Form::model($customer, ['action' => ['CustomerController@update', $customer->id], 'method' =>
    'patch'])
    !!}
@else
    {!! Form::open(array('action' => 'CustomerController@store', 'files'=>false)) !!}
@endif
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Customer Details
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group{!! $errors->has('customerCode') ? ' has-error' : '' !!}">
                    {!! Form::label('customerCode', 'Customer Code') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        {!! Form::text('customerCode', null, ['class' => 'form-control','placeholder'=>'Customer Code']) !!}
                    </div>
                    {!! $errors->first('customerCode', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('customerName') ? ' has-error' : '' !!}">
                    {!! Form::label('customerName', 'Customer Name') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        {!! Form::text('customerName', null, ['class' => 'form-control','placeholder'=>'Customer Namr']) !!}

                    </div>
                    {!! $errors->first('customerName', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('contactPerson') ? ' has-error' : '' !!}">
                    {!! Form::label('contactPerson', 'contact Person') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        {!! Form::text('contactPerson', null, ['class' => 'form-control','placeholder'=>'Contact Person']) !!}

                    </div>
                    {!! $errors->first('contactPerson', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('contactPersonPhone') ? ' has-error' : '' !!}">
                    {!! Form::label('contactPersonPhone', 'Contact Person Phone') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        {!! Form::text('contactPersonPhone', null, ['class' => 'form-control','placeholder'=>'Contact Person Phone']) !!}

                    </div>
                    {!! $errors->first('contactPersonPhone', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('companyPhone') ? ' has-error' : '' !!}">
                    {!! Form::label('companyPhone', 'Company Phone') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        {!! Form::text('companyPhone', null, ['class' => 'form-control','placeholder'=>'Company Phone']) !!}
                    </div>
                    {!! $errors->first('companyPhone', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('contactPersonEmail') ? ' has-error' : '' !!}">
                    {!! Form::label('contactPersonEmail', 'Contact Person Email') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('contactPersonEmail', null, ['class' => 'form-control','placeholder'=>'Contact Person Email']) !!}
                    </div>
                    {!! $errors->first('contactPersonEmail', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('companyEmail') ? ' has-error' : '' !!}">
                    {!! Form::label('companyEmail', 'Company Email') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('companyEmail', null, ['class' => 'form-control','placeholder'=>'Company Email']) !!}
                    </div>
                    {!! $errors->first('companyEmail', '<p class="help-block">:message</p>') !!}
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Delivery & Shipping Details
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group{!! $errors->has('street') ? ' has-error' : '' !!}">
                    {!! Form::label('street', 'Street') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        {!! Form::text('street', null, ['class' => 'form-control','placeholder'=>'Street']) !!}
                    </div>
                    {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('zipCode') ? ' has-error' : '' !!}">
                    {!! Form::label('zipCode', 'ZipCode') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                        {!! Form::text('zipCode', null, ['class' => 'form-control','placeholder'=>'Zip Code']) !!}
                    </div>
                    {!! $errors->first('zipCode', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('city') ? ' has-error' : '' !!}">
                    {!! Form::label('city', 'City') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                        {!! Form::text('city', null, ['class' => 'form-control','placeholder'=>'City']) !!}
                    </div>
                    {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('country') ? ' has-error' : '' !!}">
                    {!! Form::label('country', 'Country') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                        {!! Form::select('country',$countries, null, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('state') ? ' has-error' : '' !!}">
                    {!! Form::label('state', 'State') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                        {!! Form::text('state', null, ['class' => 'form-control','placeholder'=>'State']) !!}
                    </div>
                    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('addressName1') ? ' has-error' : '' !!}">
                    {!! Form::label('addressName1', 'Address Name 1') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-building"></i></span>
                        {!! Form::text('addressName1', null, ['class' => 'form-control','placeholder'=>'Address Name 1']) !!}
                    </div>
                    {!! $errors->first('addressName1', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('addressName2') ? ' has-error' : '' !!}">
                    {!! Form::label('addressName2', 'Address Name 2') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-building"></i></span>
                        {!! Form::text('addressName2', null, ['class' => 'form-control','placeholder'=>'Address Name 2']) !!}
                    </div>
                    {!! $errors->first('addressName2', '<p class="help-block">:message</p>') !!}
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Account Details
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group{!! $errors->has('creditLimit') ? ' has-error' : '' !!}">
                    {!! Form::label('creditLimit', 'Credit Limit') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('creditLimit', null, ['class' => 'form-control','placeholder'=>'Credit Limit']) !!}
                    </div>
                    {!! $errors->first('creditLimit', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('discount') ? ' has-error' : '' !!}">
                    {!! Form::label('discount', 'Discount') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                        {!! Form::text('discount', null, ['class' => 'form-control','placeholder'=>'Discount']) !!}
                    </div>
                    {!! $errors->first('discount', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group{!! $errors->has('active') ? ' has-error' : '' !!}">
                    {!! Form::label('active', 'Active') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-ban"></i></span>
                        {!! Form::select('active',array('1'=>'Active','0'=>'Disabled'), 1, ['class' => 'form-control']) !!}
                    </div>
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group{!! $errors->has('disableReason') ? ' has-error' : '' !!}">
                    {!! Form::label('disableReason', 'Disable Reason') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                        {!! Form::text('disableReason', null, ['class' => 'form-control','placeholder'=>'Disable Reason']) !!}
                    </div>
                    {!! $errors->first('disableReason', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-flat bg-green btn-block">Create Customer</button>
{!! Form::close() !!}
