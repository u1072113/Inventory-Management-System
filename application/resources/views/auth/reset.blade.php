@extends('layouts.login')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            {!!env('COMPANY_NAME')!!}
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">

            <p class="login-box-msg">Reset your Password</p>
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

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="email" placeholder="Email Address" class="form-control" name="email"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" placeholder="Password" class="form-control" name="password">
                    </div>
                </div>

                <div class="form-group">


                    <div class="col-md-12">
                        <input type="password" placeholder="Confirm Password" class="form-control"
                               name="password_confirmation">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            Reset Password
                        </button>
                    </div>
                </div>
            </form>
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
