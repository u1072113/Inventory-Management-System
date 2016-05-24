<h1 class="text-center">Department Menu</h1>
<ul>
    <li {{ HTML::current('department', 'department') }}><a href="/department"><i class='fa fa-users'></i> View Departments(12)</a></li>
    <li {{ HTML::current('department/create', 'department/create') }}><a href="/department/create"><i class='fa fa-plus'></i> Add Departments</a></li>
</ul>