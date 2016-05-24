@extends('layouts.master')

@section('title')
    Your Settings
@endsection



@section('content')
    <section class="content-header">
        <h1>
            Manage your Settings

        </h1>

    </section>
    <hr/>
    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default cls-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Company Settings
                    </h3>
                </div>

                <div class="panel-body">
                    {!! Form::model($company, ['action' => ['CompanyController@update', $company->id], 'method' =>
                     'patch'])
                     !!}
                    <div class="form-group{!! $errors->has('companyName') ? ' has-error' : '' !!}">
                        {!! Form::label('companyName', 'Company Name') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            {!! Form::text('companyName', null, ['class' => 'form-control','placeholder'=>'']) !!}
                        </div>
                        {!! $errors->first('companyName', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('country') ? ' has-error' : '' !!}">
                        {!! Form::label('country', 'Country') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                            {!! Form::select('country',$countries, null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('city') ? ' has-error' : '' !!}">
                        {!! Form::label('city', 'City') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                            {!! Form::text('city', null, ['class' => 'form-control','placeholder'=>'City']) !!}
                        </div>
                        {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('defaultCurrency') ? ' has-error' : '' !!}">
                        {!! Form::label('defaultCurrency', 'Currency') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                            {!! Form::select('defaultCurrency',$currency, null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('defaultCurrency', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('street') ? ' has-error' : '' !!}">
                        {!! Form::label('street', 'Street') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                            {!! Form::text('street', null, ['class' => 'form-control','placeholder'=>'Street']) !!}
                        </div>
                        {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('phone') ? ' has-error' : '' !!}">
                        {!! Form::label('phone', 'Phone') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            {!! Form::text('phone', null, ['class' => 'form-control','placeholder'=>'Company Telephone']) !!}
                        </div>
                        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('zipCode') ? ' has-error' : '' !!}">
                        {!! Form::label('zipCode', 'Zip Code') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            {!! Form::text('zipCode', null, ['class' => 'form-control','placeholder'=>'Zip Code']) !!}
                        </div>
                        {!! $errors->first('zipCode', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('companySlogan') ? ' has-error' : '' !!}">
                        {!! Form::label('companySlogan', 'Company Slogan') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                            {!! Form::text('companySlogan', null, ['class' => 'form-control','placeholder'=>'Company Slogan']) !!}
                        </div>
                        {!! $errors->first('companySlogan', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group{!! $errors->has('defaultLpoTaxAmmount') ? ' has-error' : '' !!}">
                        {!! Form::label('defaultLpoTaxAmmount', 'Default Lpo Tax Ammount') !!}
                        <div class="input-group">
                            {!! Form::text('defaultLpoTaxAmmount', 16, ['class' => 'form-control','placeholder'=>'Default Tax on LPO']) !!}
                            <span class="input-group-addon">%</span>
                        </div>
                        {!! $errors->first('defaultLpoTaxAmmount', '<p class="help-block">:message</p>') !!}
                    </div>


                    <div class="form-group{!! $errors->has('language') ? ' has-error' : '' !!}">
                        {!! Form::label('language', 'Default Application Language') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                            {!! Form::select('language',array('en'=>'English','ru'=>'Russian','de'=>'German','es'=>'Spanish','nl'=>'Dutch','fr'=>'French','pt'=>'Portuguese','in'=>'Indonesian'), null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('language', '<p class="help-block">:message</p>') !!}
                    </div>
                    <button type="submit" class="btn btn-flat bg-green btn-block">Save Company Info</button>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">


        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Update Application Theme</h3>
                </div>
                {!! Form::model($settings, ['action' => ['SettingController@update', $settings->id],
                 'method' =>
                 'patch'])
                 !!}
                <div class="box-body">

                    <div class="form-group{!! $errors->has('appTheme') ? ' has-error' : '' !!}">
                        {!! Form::label('appTheme', 'Your App Theme') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-cogs"></i></span>
                            {!!
                            Form::select('appTheme',array("skin-blue"=>"skin-blue","skin-yellow"=>"skin-yellow","skin-purple"=>"skin-purple","skin-green"=>"skin-green","skin-red"=>"skin-red","skin-black"=>"skin-black"),
                            null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('appTheme', '<p class="help-block">:message</p>') !!}
                    </div>

                    
                    <div class="form-group{!! $errors->has('paginationDefault') ? ' has-error' : '' !!}">
                        {!! Form::label('paginationDefault', 'Pagination Default') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            {!! Form::text('paginationDefault', null, ['class' => 'form-control','placeholder'=>'Default Pagination View']) !!}

                        </div>
                        {!! $errors->first('paginationDefault', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-block btn-flat bg-green">Update Themes</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('js')
    $('select').select2({
    allowClear: true,
    placeholder: "Please Select ",
    //Allow manually entered text in drop down.
    createSearchChoice: function (term, data) {
    if ($(data).filter(function () {
    return this.text.localeCompare(term) === 0;
    }).length === 0) {
    return {id: term, text: term};
    }
    },
    });
    //Colorpicker
    $(".colorpicker").colorpicker();


@endsection