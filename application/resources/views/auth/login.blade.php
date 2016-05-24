@extends('layouts.login')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            {!!env('COMPANY_NAME')!!}
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="{{ url('/auth/login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <hr/>
            <div class="row">
                <div class="col-md-6">
                    <a href="{{url('password/email')}}"><i class="fa fa-lock"></i> Forgot Password</a>
                </div>
                <div class="col-md-6 ">
                    <a href="{{url('auth/register')}}" class="text-center pull-right"><i class="fa fa-user"></i> New
                        User</a>
                </div>
            </div>

        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->


@endsection
