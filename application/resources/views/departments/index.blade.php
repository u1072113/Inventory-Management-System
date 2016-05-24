@extends('layouts.master')

@section('title')
    View All Departments
@endsection
@section('report')
    <div aria-label="Actions" role="group" class="btn-group">
        <a href="{{url('/department/stock/export?type=xlsx')}}" class="btn btn-warning"><i
                    class="fa fa-file-excel-o"></i> Excel</a>
        <a  href="#email-popup"  data-url="{{action('DepartmentController@export')}}"  class="email-popup-link btn btn-primary"
            data-url="#"><i
                    class="fa fa-envelope"></i> Email To</a>
        <a class="btn btn-info" href="{{url('/department/stock/export?type=csv')}}"><i class="fa fa-file"></i> CSV</a>
    </div>
@endsection
@section('sidebar')

    @include('departments.form')


@endsection

@section('content')
    <section class="content-header">
        <h1>
             {!! Helper::translateAndShorten('Departments And Budgets','viewdepartment',50)!!}({{$departments->total()}})
        </h1>

    </section>
    <hr/>
    <table class="table table-paper table-condensed table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>{!!HTML::sort('DepartmentController@index','departments.name','Department Name','viewdepartment')!!}(id)</th>
            <th>{!!HTML::sort('DepartmentController@index','departments.budgetLimit','Limit','viewdepartment')!!}</th>
            <th>{!!HTML::sort('DepartmentController@index','dispatchcount','Count','viewdepartment')!!}</th>
            <th>{!!HTML::sort('DepartmentController@index','dispatchsum','Sum','viewdepartment')!!}</th>
            <th> {!!HTML::sort('DepartmentController@index','departments.departmentEmail','Email','viewdepartment')!!}</th>
            @include('departments.custom.tableheader')
            <th>{!!HTML::sort('DepartmentController@index','departments.budgetStartDate','Start Date','viewdepartment')!!}</th>
            <th>{!!HTML::sort('DepartmentController@index','departments.budgetEndDate','End Date','viewdepartment')!!}</th>
            <th>Actions</th>

        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @foreach ($departments as $department)
<?php echo $department; ?>
        

            @if($department->dispatchsum > $department->budgetLimit)
                <tr class="danger">
            @else
                <tr class="">
                    @endif
                    <th scope="row">{{$i}}</th>
                    <td>{{ucwords($department->name)}}({{$department->id}})</td>
                    <td>{{$department->present()->budgetLimit}}</td>
                    <td class="text-center">{{$department->dispatchcount}} </td>
                    <td class="text-center">
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar progress-bar-primary" style="width: {{$department->present()->percentage}}"></div>
                        </div>
                        {{$department->present()->dispatchSum}}
                    </td>
                    <td>{{$department->departmentEmail}} </td>
                    @include('departments.custom.tablefields')
                    <td>{{$department->present()->budgetStartDate}}</td>
                    <td>{{$department->present()->budgetEndDate}}</td>
                    <td class="text-center">
                        @if(isset($restore))
                            <a href="{{action('DepartmentController@restore', $department->id)}}"
                               class="btn btn-flat bg-purple"><i
                                        class="fa fa-undo"></i></a>
                        @else
                            <div aria-label="Actions" role="group" class="btn-group">
                                <a href="#delete-popup" class="open-popup-link btn btn-flat bg-red"
                                   data-url="{{action('DepartmentController@destroy', $department->id)}}"><i
                                            class="fa fa-remove"></i></a>
                                <a class="btn btn-flat bg-blue" href="{{action('DepartmentController@edit', $department->id)}}">
                                    <i
                                            class="   fa fa-edit"></i></a>
                            </div>
                        @endif
                    </td>


                </tr>
                <?php $i++; ?>
                </tr>
                @endforeach

        </tbody>
    </table>
    <hr/>
    <div class="text-center">
        {!!$departments->appends($sort)->render()!!}
    </div>
    <div class="text-center">
        <div class="btn-group" data-toggle="buttons">

            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(10)}} id="q156" name="quality[25]" value="10"/> 10
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(20)}} id="q157" name="quality[25]" value="20"/> 20
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(30)}} id="q158" name="quality[25]" value="30"/> 30
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(40)}} id="q159" name="quality[25]" value="40"/> 40
                Items Per Page
            </label>
            <label class="btn btn-default">
                <input type="radio" class="pag" {{\App\Helper::checked(50)}} id="q160" name="quality[25]" value="50"/> 50
                Items Per Page
            </label>
        </div>
    </div>
    <hr/>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Department Chart</h3>
        </div>
        <div class="box-body">
            <div class="stats-container" id="stats-container"></div>
        </div>
    </div>
@endsection

@section('js')



    @if(isset($departmentChart))
        var data = JSON.parse('{!! $departmentChart !!}');
        new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'stats-container',
        data: data,
        xkey: 'name',
        ykeys: ['dispatchcount','dispatchsum'],
        labels: ['Items Dispatched','Dispatch Sum']
        });
    @endif
@endsection
