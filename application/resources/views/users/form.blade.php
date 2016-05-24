<div class="panel panel-default cls-panel">
    <div class="panel-heading">
        <h3 class="panel-title">
             {!! Helper::translateAndShorten('Create User','createuser',50)!!}
        </h3>
    </div>
    @if(isset($user))
        {!! Form::model($user, ['action' => ['UserController@update', $user->id], 'method' =>
        'patch'])
        !!}
    @else
        {!! Form::open(array('action' => 'UserController@store', 'onsubmit' => 'return postForm();',
        'files'=>false)) !!}
    @endif
    <div class="panel-body">
        <div class="form-group{!! $errors->has('name') ? ' has-error' : '' !!}">
            {!! Form::label('name',  trans('createuser.UserName') ) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
            {!! Form::label('email', trans('createuser.Email')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{!! $errors->has('departmentID') ? ' has-error' : '' !!}">
            {!! Form::label('departmentID', trans('createuser.User Department')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                {!! Form::select('departmentID',$departments, null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('departmentID', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{!! $errors->has('role_id') ? ' has-error' : '' !!}">
            {!! Form::label('role_id', trans('createuser.User Role')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-ban"></i></span>
                {!! Form::select('role_id',$roles, null, ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{!! $errors->has('password') ? ' has-error' : '' !!}">
            {!! Form::label('password', trans('createuser.Password(Resets Password Leave Blank not to set anything)')) !!}
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>
            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group{!! $errors->has('image') ? ' has-error' : '' !!}">
                    <div class="text-center"> {!! Form::label('image', trans('createuser.Upload User Avatar')) !!}</div>
                    <div class="input-group col-md-12">
                        <div class="dropzone col-md-12" id="image"></div>
                    </div>
                    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
            <input type="hidden" class="avatar" name="avatar" id="avatar"/>
        <input type="hidden" value="{{Auth::user()->companyId}}" name="companyId" />

    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-flat bg-green btn-block"><i class="fa fa-user-plus"></i> Save User</button>
    </div>
    {!! Form::close() !!}
</div>
