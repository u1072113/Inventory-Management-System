@foreach($model as $table)


    <div class="form-group@{!! $errors->has('{{$table->columnName}}') ? ' has-error' : '' !!}">
        @{!! Form::label('{{$table->columnName}}', '{{$table->userView}}') !!}
        <div class="input-group">
            <span class="input-group-addon"><i class="fa {{$table->fontawesome}}"></i></span>
            @{!! Form::text('{{$table->columnName}}', null, ['class' => 'form-control']) !!}
        </div>
        @{!! $errors->first('{{$table->columnName}}', '<p class="help-block">:message</p>') !!}
    </div>


@endforeach