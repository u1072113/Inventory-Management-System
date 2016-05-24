@extends('layouts.login')
<style>
    .center {
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
        margin-top: auto;
    }

    .cls-panel {
        margin-top: 40px;
    }
</style>
@section('title')
    404 Error
@endsection

@section('content')
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default cls-panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    No Permissions
                </h3>
            </div>

            <div class="panel-body">
                <div class="hero-unit center">
                    <h1>Insufficient Permissions
                        <small><font face="Tahoma" color="red">Error 403</font></small>
                    </h1>
                    <br/>

                    <a href="/Inventory" class="btn btn-large btn-info"><i
                                class="icon-home icon-white"></i>Dashboard</a>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

@endsection