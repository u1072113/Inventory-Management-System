@extends('layouts.master')

@section('title')
    User Roles
@endsection



@section('content')
    <h1 class="text-center">User Roles</h1>
    <hr/>
    <div class="alert alert-danger">You are not allowed to edit this this is displayed here for information purposes
        only
    </div>
    <table class="table table-paper table-condensed table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Date Created</th>
            <th>Date Updates</th>
        </tr>
        </thead>
        <tbody>

        <?php $i = 1; ?>
        @foreach ($userroles as $role)
            <tr class="">
                <th scope="row">{{$i}}</th>
                <td>{{ucwords($role->name)}}</td>
                <td>{{$role->description}}</td>
                <td>{{Carbon::parse($role->created_at)->format('d/m/Y')}} </td>
                <td>{{Carbon::parse($role->updated_at)->format('d/m/Y')}} </td>
                <?php $i++; ?>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection

@section('js')

@endsection