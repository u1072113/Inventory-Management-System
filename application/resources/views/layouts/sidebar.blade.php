<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img alt="User Image" class="img-circle" src="{{App\Helper::avatar()}}">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->name}}</p>

                <a href="#"><i
                            class="fa fa-circle text-success"></i> {!! Helper::translateAndShorten('Online','sidebar',200)!!}
                    ({{Auth::user()->company->companyName}}
                    )</a>
            </div>
            @if(Auth::user()->role_id== 1)
                {!! Form::open(array('action' => 'UserController@postLogin', 'class'=>'form-sidebar ','onsubmit' => 'return postForm();', 'files'=>false)) !!}
                <div class="form-group{!! $errors->has('userid') ? ' has-error' : '' !!}">
                    {!! Form::label('userid',  trans('sidebar.Quick User Change') ) !!}
                    <div class="input-group">
                        {!! Form::select('userid',$users, Auth::user()->id, ['class' => 'choose-users form-control ']) !!}
                        <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">{{ trans('sidebar.Go') }}</button>
                    </span>
                    </div>
                    {!! $errors->first('userid', '<p class="help-block">:message</p>') !!}
                </div>
                {!! Form::close() !!}
            @endif
        </div>
        @if(Request::segment(1)!='home')
                <!-- search form -->
        <form class="sidebar-form" method="get" action="{{url(Request::segment(1))}}">
            <div class="input-group">
                <input type="text" placeholder="Search..." value="{{$search}}" class="form-control search-box"
                       name="search">
              <span class="input-group-btn">
                  @if($search)
                      <a class="btn btn-flat" href="{{url(Request::segment(1))}}" id="search-btn" type="submit"><i
                                  class="fa fa-remove"></i></a>
                  @else
                      <button class="btn btn-flat" id="search-btn" type="submit"><i class="fa fa-search"></i></button>
                  @endif

              </span>
            </div>
        </form>

        <div class="report-buttons text-center" style="">
            <div class="btn-group">
                <a class="btn btn-flat  bg-orange" href="{{url(Request::segment(1)."/stock/export?type=xlsx")}}"><i
                            class="fa fa-file-excel-o"></i> Excel</a>

                <a class="btn btn-flat  bg-purple" href="{{url(Request::segment(1)."/stock/export?type=csv")}}"><i
                            class="fa fa-file-pdf-o"></i> CSV</a>

                <div data-url="{{url(Request::segment(1)."/stock/export?type=email")}}"
                     class="btn btn-flat  bg-green email-popup-link send-email"><i class="fa fa-envelope"></i> Email
                </div>
            </div>
        </div>
        @endif
                <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header"> {!! Helper::translateAndShorten('MAIN NAVIGATION','sidebar',50)!!}</li>

             <li {{ HTML::current('home', 'home') }} ><a href="{{url('home')}}"><i
                                    class="fa fa-dashboard"></i>
                            {!! Helper::translateAndShorten('Dashboard','sidebar',15)!!}</a>
            </li>

            <!-- Products-->
            @if(Auth::user()->role_id== 1 || Auth::user()->role_id== 2 || Auth::user()->role_id== 3 || Auth::user()->role_id== 5)

            <li {{ HTML::current('product/*', 'product/*') }}>
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>{!! Helper::translateAndShorten('Products','sidebar',15)!!} </span> <i
                            class="fa fa-angle-left pull-right"></i>
                    <span class="label label-primary pull-right">{{$stockCount}}</span>
                </a>
                <ul class="treeview-menu">
                 @if(!Auth::user()->role_id== 5 || Auth::user()->role_id== 1 || Auth::user()->role_id== 2 || Auth::user()->role_id== 3)
                    <li {{ HTML::current('product/create', 'product/create') }} ><a
                                href="{{url('/product/create')}}"><i
                                    class='fa fa-plus'></i> {!! Helper::translateAndShorten('Add Product','sidebar',15)!!}
                        </a></li>
                        @endif
                    <li {{ HTML::current('product', 'product') }}><a href="{{url('product')}}"><i
                                    class='fa fa-archive'></i> {!! Helper::translateAndShorten('Stock Items','sidebar',15)!!}
                        </a></li>

                 @if(!Auth::user()->role_id== 5 || Auth::user()->role_id== 1 || Auth::user()->role_id== 2 || Auth::user()->role_id== 3)

                    <li {{ HTML::current('product/category/show', 'product/category/show') }}><a
                                href="{{url('/product/category/show')}}"><i
                                    class='fa fa-tasks'></i> {!! Helper::translateAndShorten('Category','sidebar',15)!!}
                        </a>
                    </li>

                    <li {{ HTML::current('product/stock/finished', 'product/stock/finished') }}><a
                                href="{{url('/product/stock/finished')}}"><i
                                    class='fa fa-tasks'></i> {!! Helper::translateAndShorten('Out of Stock','sidebar',15)!!}
                        </a>
                    </li>
                    <li {{ HTML::current('product/stock/deleted', 'product/stock/deleted') }}><a
                                href="{{url('/product/stock/deleted')}}"><i
                                    class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Items','sidebar',15)!!}
                        </a></li>

                    <li {{ HTML::current('product/stock/warning', 'product/stock/warning') }}><a
                                href="{{url('/product/stock/warning')}}"><i
                                    class='fa fa-warning'></i> {!! Helper::translateAndShorten('Low Stock','sidebar',15)!!}
                        </a>
                    </li>
                    <li {{ HTML::current('product/stock/import', 'product/stock/import') }}><a
                                href="{{url('/product/stock/import')}}"><i
                                    class='fa fa-upload'></i> {!! Helper::translateAndShorten('Upload CSV FIle','sidebar', 15)!!}
                        </a></li>
                        @endif

                </ul>
            </li>
            @endif
       
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )

            <li {{ HTML::current('order/*', 'order/*') }}>
                <a href="#">
                    <i class="fa fa-level-up"></i>
                    <span>  {!! Helper::translateAndShorten('Orders','sidebar',15)!!} </span> <i
                            class="fa fa-angle-left pull-right"></i>
                    <span class="label label-primary pull-right">{{$purchaseOrder}}</span>
                </a>
                <ul class="treeview-menu">
                    <li {{ HTML::current('order/create', 'order/create') }}><a href="{{url('order/create')}}"><i
                                    class='fa fa-plus'></i> {!! Helper::translateAndShorten('New Order','sidebar',15)!!}
                        </a></li>
                    <li {{ HTML::current('order', 'order') }}><a href="{{url('order')}}"><i
                                    class='fa fa-users'></i> {!! Helper::translateAndShorten('Orders','sidebar',15)!!}
                        </a></li>
                    <li {{ HTML::current('order/list/deleted', 'order/list/deleted') }}><a
                                href="{{url('order/list/deleted')}}"><i
                                    class='fa fa-ban'></i>
                            {!! Helper::translateAndShorten('Deleted Orders','sidebar',15)!!}</a></li>


                </ul>
            </li>
            @endif
            
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)

                        <!-- Restock-->
                <li {{ HTML::current('restock/*', 'restock/*') }}>
                    <a href="#">
                        <i class="fa fa-level-up"></i>
                        <span> {!! Helper::translateAndShorten('Restock','sidebar',15)!!} </span> <i
                                class="fa fa-angle-left pull-right"></i>
                        <span class="label label-primary pull-right">{{$restockCount}}</span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ HTML::current('restock/create', 'restock/create') }}><a
                                    href="{{url('restock/create')}}"><i
                                        class='fa fa-plus'></i> {!! Helper::translateAndShorten('Add Items to Stock','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('restock', 'restock') }}><a href="{{url('restock')}}"><i
                                        class='fa fa-users'></i> {!! Helper::translateAndShorten('Restocked Items','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('restock/stock/deleted', 'restock/stock/deleted') }}><a
                                    href="{{url('restock/stock/deleted')}}"><i
                                        class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Items','sidebar',15)!!}
                            </a></li>
                    </ul>
                </li>
                @endif
                 @if(!Auth::user()->role_id== 5 || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4)

                <!-- Dispatch-->
                <li {{ HTML::current('dispatch/*', 'dispatch/*') }}>
                    <a href="#">
                        <i class="fa fa-user-plus"></i>
                        <span> {!! Helper::translateAndShorten('Dispatch','sidebar',15)!!} </span> <i
                                class="fa fa-angle-left pull-right"></i>
                        <span class="label label-primary pull-right">{{$dispatchCount}}</span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ HTML::current('dispatch/create', 'dispatch/create') }} ><a
                                    href="{{url('/dispatch/create')}}"><i
                                        class='fa fa-plus'></i> {!! Helper::translateAndShorten('Dispatch Item','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('dispatch', 'dispatch') }}><a href="{{url('dispatch')}}"><i
                                        class='fa fa-shopping-cart'></i> {!! Helper::translateAndShorten('VIew Dispatches','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('dispatch/stock/deleted', 'dispatch/stock/deleted') }}><a
                                    href="{{url('dispatch/stock/deleted')}}"><i
                                        class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Items','sidebar',15)!!}
                            </a></li>
                 
                    </ul>
                </li>
                @endif
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )

                <!-- Suppliers-->
                <li {{ HTML::current('supplier/*', 'supplier/*') }}>
                    <a href="#">
                        <i class="fa fa-truck"></i>
                        <span> {!! Helper::translateAndShorten('Suppliers','sidebar',15)!!} </span> <i
                                class="fa fa-angle-left pull-right"></i>
                        <span class="label label-primary pull-right">{{$supplierCount}}</span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ HTML::current('supplier/create', 'supplier/create') }}><a
                                    href="{{url('supplier/create')}}"><i
                                        class='fa fa-plus'></i> {!! Helper::translateAndShorten('Add Supplier','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('supplier', 'supplier') }}><a href="{{url('supplier')}}"><i
                                        class='fa fa-users'></i> {!! Helper::translateAndShorten('Suppliers','sidebar',15)!!}
                            </a></li>

                        <li {{ HTML::current('supplier/stock/deleted', 'supplier/stock/deleted') }}><a
                                    href="{{url('supplier/stock/deleted')}}"><i
                                        class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Items','sidebar',15)!!}
                            </a></li>
                    </ul>
                </li>
                @endif
          
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )

                <li {{ HTML::current('staff/*', 'staff/*') }}>
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span> {!! Helper::translateAndShorten('Staff','sidebar',15)!!} </span>
                        <i class="fa fa-angle-left pull-right"></i>
                        <span class="label label-primary pull-right">{{$staffCount}}</span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ HTML::current('staff/create', 'staff/create') }}><a href="{{url('staff/create')}}"><i
                                        class='fa fa-plus'></i> {!! Helper::translateAndShorten('New Staff','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('staff', 'staff') }}><a href="{{url('staff')}}"><i
                                        class='fa fa-users'></i> {!! Helper::translateAndShorten('View Staff','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('staff/view/deleted', 'staff/view/deleted') }}><a
                                    href="{{url('restock/stock/deleted')}}"><i
                                        class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Staff','sidebar',15)!!}
                            </a></li>
                    </ul>
                </li>
                @endif
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )

                <!-- Department-->
                <li {{ HTML::current('department/*', 'department/*') }}>
                    <a href="#">
                        <i class="fa fa-briefcase"></i>
                        <span> {!! Helper::translateAndShorten('Departments','sidebar',15)!!} </span> <i
                                class="fa fa-angle-left pull-right"></i>
                        <span class="label label-primary pull-right">{{$departmentCount}}</span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ HTML::current('department/create', 'department/create') }}><a
                                    href="{{url('department/create')}}"><i
                                        class='fa fa-plus'></i> {!! Helper::translateAndShorten('Add Departments','sidebar',15)!!}
                            </a></li>
                        <li {{ HTML::current('department', 'department') }}><a href="{{url('department')}}"><i
                                        class='fa fa-users'></i> {!! Helper::translateAndShorten('View Departments','sidebar',15)!!}
                            </a></li>

                    </ul>
                </li>
                @endif

                <!-- Users-->
                @if(Auth::user()->role_id == 1)
                    <li {{ HTML::current('user/*', 'user/*') }}>
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span> {!! Helper::translateAndShorten('Users','sidebar',15)!!} </span> <i
                                    class="fa fa-angle-left pull-right"></i>
                            <span class="label label-primary pull-right">{{$userCount}}</span>
                        </a>
                        <ul class="treeview-menu">
                            <li {{ HTML::current('user/create', 'user/create') }}><a
                                        href="{{url('user/create')}}"><i
                                            class='fa fa-plus'></i> {!! Helper::translateAndShorten('Create User','sidebar',15)!!}
                                </a></li>
                            <li {{ HTML::current('user', 'user') }}><a href="{{url('user')}}"><i
                                            class='fa fa-users'></i> {!! Helper::translateAndShorten('All Users','sidebar',15)!!}
                                </a></li>

                            <li {{ HTML::current('roles', 'roles') }}><a
                                        href="{{url('roles')}}"><i
                                            class='fa fa-ban'></i> {!! Helper::translateAndShorten('User Roles','sidebar',15)!!}
                                </a></li>
                            <li {{ HTML::current('supplier/stock/deleted', 'supplier/stock/deleted') }}><a
                                        href="{{url('user/stock/deleted')}}"><i
                                            class='fa fa-eye-slash'></i> {!! Helper::translateAndShorten('Deleted Users','sidebar',15)!!}
                                </a>
                            </li>
                            <li {{ HTML::current('user/stock/import', 'user/stock/import') }}><a
                                        href="{{url('/user/stock/import')}}"><i
                                            class='fa fa-upload'></i> {!! Helper::translateAndShorten('Upload CSV FIle','sidebar',15)!!}
                                </a></li>
                        </ul>
                    </li>
                @endif

                <li {{ HTML::current('setting/'.Auth::user()->id.'/edit', 'setting/'.Auth::user()->id.'/edit') }}><a
                            href="{{url('setting/'.Auth::user()->id.'/edit')}}" class="bg-purple"><i
                                class='fa fa-cog'></i> {!! Helper::translateAndShorten('Your Settings','sidebar',15)!!}
                    </a></li>
        </ul>


    </section>
    <!-- /.sidebar -->
</aside>