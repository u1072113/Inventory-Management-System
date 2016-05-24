@extends('layouts.login')

@section('content')
	<div class="login-box">
		<div class="login-logo">
			{!!env('COMPANY_NAME')!!}
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Sign up to start your session</p>
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
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="form-group{!! $errors->has('companyName') ? ' has-error' : '' !!}">
					<div class="col-md-12">
						{!! Form::text('companyName', null, ['class' => 'form-control','placeholder'=>'Company Name']) !!}
						{!! $errors->first('companyName', '<p class="help-block">:message</p>') !!}
					</div>
				</div>

				<div class="form-group">

					<div class="col-md-12">
						<input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<input type="password" placeholder="Password" class="form-control" name="password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<input type="password" placeholder="confirm Password" class="form-control" name="password_confirmation">
					</div>
				</div>
				


				<div class="form-group">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-block">
							Register
						</button>
					</div>
				</div>
			</form>
			<hr/>
			<div class="row">
				<div class="col-md-6">
					<a href="{{url('password/email')}}"><i class="fa fa-lock"></i> Forgot Password</a>
				</div>
				<div class="col-md-6 ">
					<a href="{{url('auth/login')}}" class="text-center pull-right"><i class="fa fa-user"></i> Login</a>
				</div>
			</div>


		</div>
		<!-- /.login-box-body -->
	</div><!-- /.login-box -->

@endsection
