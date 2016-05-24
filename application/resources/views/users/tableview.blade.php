<section class="content-header">
    <h1>
        Users({{$users->total()}})
        <small>{{$message}}</small>
    </h1>

</section>
<hr/>

<table class="table table-paper table-condensed table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>{!!HTML::sort('UserController@index','name','Username','viewusers')!!}</th>
        <th>{!!HTML::sort('UserController@index','email','Email','viewusers')!!}</th>
        <th>{!!HTML::sort('UserController@index','roles.name','User Role','viewusers')!!}</th>
        <th>{!!HTML::sort('UserController@index','departments.name','Department','viewusers')!!}</th>
        <th>{!!HTML::sort('UserController@index','date_creates','Date Created','viewusers')!!}</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php $i = 1; ?>
    @foreach ($users as $user)
        <tr class="">
            <th scope="row">{{$i}}</th>
            <td>{{ucwords($user->name)}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->role->name}}</td>
            @if($user->department)
                <td>{{$user->department->name}}</td>
            @else
                <td>-</td>
            @endif
            <td>{{Carbon::parse($user->created_at)->format('d/m/Y')}} </td>

            <td class="text-center">
                @if(isset($restore))
                    <a href="{{action('UserController@restore', $user->id)}}" class="btn btn-flat bg-purple"><i
                                class="fa fa-undo"></i></a>
                @else
                    <div aria-label="Actions" role="group" class="btn-group">
                        <div class="open-popup-link btn btn-flat bg-red delete-button"
                           data-url="{{action('UserController@destroy', $user->id)}}"><i
                                    class="fa fa-remove"></i></div>
                        <a class="btn btn-flat bg-blue" href="{{action('UserController@edit', $user->id)}}"> <i
                                    class="   fa fa-edit"></i></a>
                    </div>
                @endif
            </td>

            <?php $i++; ?>
        </tr>
    @endforeach

    </tbody>
</table>
<hr/>
<div class="text-center">
    {!!$users->appends($sort)->render()!!}
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