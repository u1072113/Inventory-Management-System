<div class="panel panel-default cls-panel" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel-heading" role="tab" id="dispatchform">
        <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true"
            aria-controls="collapseOne">
            {!! Helper::translateAndShorten('Dispatch a Product','dispatchitem',50)!!}
        </h3>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="dispatchform">
        @if(isset($dispatch))
            {!! Form::model($dispatch, ['action' => ['DispatchController@update', $dispatch->id], 'method' =>
            'patch'])
            !!}
        @else
            {!! Form::open(array('action' => 'DispatchController@store')) !!}
        @endif
        <div class="panel-body">
            <div class="form-group{!! $errors->has('dispatchedItem') ? ' has-error' : '' !!}">
                {!! Form::label('dispatchedItem',  trans('dispatchitem.Item to Dispatch') ) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                    {!! Form::select('dispatchedItem',$products, null, ['class' => 'form-control dispselect']) !!}
                </div>
                {!! $errors->first('dispatchedItem', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group{!! $errors->has('dispatchedTo') ? ' has-error' : '' !!}">
                {!! Form::label('dispatchedTo', trans('dispatchitem.Dispatched To')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    {!! Form::select('dispatchedTo',array(), null, ['class' => 'form-control catselect']) !!}
                    <span v-on:click="openModal" class="input-group-addon bg-green"><i class="fa fa-plus"></i></span>
                </div>
                {!! $errors->first('dispatchedTo', '<p class="help-block">:message</p>') !!}
            </div>

            <div class="form-group{!! $errors->has('amount') ? ' has-error' : '' !!}">
                {!! Form::label('amount', trans('dispatchitem.Dispatched Amount')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sort-amount-asc"></i></span>
                    {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                </div>
                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
            </div>
            @include('dispatches.custom.inputfields')
            <div class="form-group{!! $errors->has('remarks') ? ' has-error' : '' !!}">
                {!! Form::label('remarks', trans('dispatchitem.Remarks')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-comments"></i></span>
                    {!! Form::text('remarks', null, ['class' => 'form-control']) !!}
                </div>
                {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
            </div>


        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-flat bg-green  btn-block">Dispatch</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="easy-modal" id="modal1">
    <div class="header modal-header text-center">
        <h3>Add A new User</h3>
    </div>
    <div class="easy-modal-inner">
        <div class="form-group">
            <input type="text" class="form-control" v-model="name" placeholder="Staff Name">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" v-model="email"
                   placeholder="Email">
        </div>
        <div class="form-group">
            <div class="form-group{!! $errors->has('department') ? ' has-error' : '' !!}">
                {!! Form::label('department', 'Department') !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                    {!! Form::select('department',$departments, null, ['class' => 'form-control depSelect']) !!}
                    <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                </div>
                {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="submit" v-on:click="addCategory"
                        v-show="name.length > 3 && email.length > 3"
                        class="btn btn-flat bg-green btn-block">Add User
                </button>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-flat bg-red btn-block" v-on:click="closeModal">Cancel</button>
            </div>
        </div>


    </div>
</div>
