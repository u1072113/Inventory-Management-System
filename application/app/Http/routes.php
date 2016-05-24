<?php

foreach (File::allFiles(__DIR__ . '/Routes') as $partial) {
    require (string)$partial;
}

Route::resource('company', 'CompanyController');
Route::resource('reports', 'ReportController');
if (env('APP_ONLINE', false) == true) {
    Route::get('/', function () {
        return View::make('layouts.homepage', array('name' => 'Taylor'));
    });
} else {
    Route::get('/', 'Auth\AuthController@getLogin');
}


Route::get('landing', 'HomeController@landing');
Route::get('test', 'TestController@index');
Route::post('user/force/login', 'UserController@postLogin');
Route::get('/user/login/auto/{id}', 'LoginController@index');
Route::group(['middleware' => ['auth', 'roles']], function () {
    Route::get('home', 'DashboardController@index');
    Route::get('home2', 'PrinterDashboardController@index');
});


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


//'hvr-sweep-to-left' => 'hvr-sweep-to-left', 'hvr-sweep-to-bottom' => 'hvr-sweep-to-bottom'
HTML::macro('current', function () {
    $Routes = func_get_args();
    $HTML = ' class=active';
    $hover = array_rand(['hvr-sweep-to-right' => 'hvr-sweep-to-right'], 1);
    $hover = 'class=' . $hover;
    foreach ($Routes as $route):

        if (Request::is($route)) {
            return $HTML;
        } elseif (str_contains($route, '*')) {
            $fallback = str_replace("/*", "", $route);
            if (Request::url() == url($fallback)) {
                return $HTML;
            }

        } else {
            return $hover;
        }
    endforeach;
});

HTML::macro('currentHeader', function () {
    $Routes = func_get_args();
    $HTML = ' class=active';
    $hover = array_rand(['hvr-sweep-to-bottom' => 'hvr-sweep-to-bottom'], 1);
    $hover = 'class=' . $hover;
    foreach ($Routes as $route):
        if (Request::is($route)) {
            return $HTML;
        } else {
            return $hover;
        }
    endforeach;
});

HTML::macro('sort', function ($controller, $column, $body, $translationFile = 'stockitems') {
    $direction = (Request::get('direction') == 'asc') ? 'desc' : 'asc';
    $sort = (Request::get('direction') == 'asc') ? 'sort-desc' : 'sort-asc';
    return link_to_action($controller, trans(strval($translationFile) . '.' . str_limit($body, 20)), ['sortBy' => $column, 'direction' => $direction], ['class' => $sort . ' translate', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $body]);
}
);



