<div class="panel panel-default cls-panel">
    <div class="panel-heading">
        <h3 class="panel-title">
             {!! Helper::translateAndShorten('Create Staff','newstaff',50)!!}
        </h3>
    </div>
    @if(isset($user))
        {!! Form::model($user, ['action' => ['StaffController@update', $user->id], 'method' =>
        'patch'])
        !!}
    @else
        {!! Form::open(array('action' => 'StaffController@store', 'onsubmit' => 'return postForm();',
        'files'=>false)) !!}
    @endif
    <div class="panel-body">
        <div class="form-group{!! $errors->has('name') ? ' has-error' : '' !!}">
            {!! Form::label('name',  trans('newstaff.Staff Name') ) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
            {!! Form::label('email', trans('newstaff.Email')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{!! $errors->has('departmentID') ? ' has-error' : '' !!}">
            {!! Form::label('departmentID', trans('newstaff.User Department')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                {!! Form::select('departmentID',$departments, null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('departmentID', '<p class="help-block">:message</p>') !!}
        </div>

        


    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-flat bg-green btn-block"><i class="fa fa-user-plus"></i> Save User</button>
    </div>
    {!! Form::close() !!}
</div>
