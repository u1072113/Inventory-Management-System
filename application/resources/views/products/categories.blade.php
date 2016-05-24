@extends('layouts.master')

@section('title')
    Product Categories
@endsection

@section('sidebar')

@endsection

@section('content')
    <section>
    <h1>
        {!! Helper::translateAndShorten('Categories','stockitems',50)!!}({{$categories->total()}})
        <small>Your Product Categories</small>
    </h1>

    </section>
    <hr/>
          <table class="table table-paper table-condensed table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Category Name</th>
                                  <th>Category Description</th>
                                  <th>Created At</th>
                                  <th>Updated At</th>
                                  <th>Delete</th>

                              </tr>
                              </thead>
                              <tbody>
                              <?php $i = 1; ?>
                              @foreach ($categories as $category)
                                      <tr class="">
                                          <th scope="row">{{$i}}</th>
                                          <td>{{ucwords($category->categoryName)}}</td>
                                          <td>{{$category->categoryDescription}}</td>
                                           <td>{{Carbon::parse($category->created_at)->format('d/m/Y')}} </td>
                                            <td>{{Carbon::parse($category->updated_at)->format('d/m/Y')}} </td>
                                          <td class="text-center">
                                              <div aria-label="Actions" role="group" class="btn-group">

                                                      <div class="open-popup-link btn btn-flat bg-red delete-button"
                                                           data-url="{{action('ProductController@categoryDelete', $category->id)}}"><i
                                                                  class="fa fa-remove"></i></div>



                                              </div>
                                          </td>
                                          <?php $i++; ?>
                                      </tr>
                                      @endforeach

                              </tbody>
                          </table>
@endsection

@section('js')

@endsection