var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.styles([
        "bootstrap.min.css",
        "_all-skins.css",
        "font-awesome.min.css",
        "select2.2min.css",
        "mystyle.css",
        "bootstrap-datepicker3.min.css",
        "hover-min.css",
        "morris.css",
        "AdminLTE.css",
        "daterangepicker-bs3.css",
        "editablegrid.css",
        "chartist.min.css",
        "bootstrap-colorpicker.min.css",
        "sweetalert.css"


    ], 'public/dist/css/dist.css', 'public/assets/css');

    mix.styles([
        "bootstrap.min.css",
        "_all-skins.css",
        "font-awesome.min.css",
        "select2.2min.css",
        "mystyle.css",
        "bootstrap-datepicker3.min.css",
        "hover-min.css",
        "morris.css",
        "AdminLTE.css",
        "daterangepicker-bs3.css",
        "editablegrid.css",
        "chartist.min.css",
        "bootstrap-colorpicker.min.css",
        "sweetalert.css"


    ], 'public/dist/css/landingpage.css', 'public/assets/css');

    mix.scripts([
        "jquery-2.1.3.min.js",
        "vue.js",
        "app.js",
        "jquery.slimscroll.js",
        "bootstrap.min.js",
        "dropzone.js",
        "select2.min.js",
        "bootstrap-datepicker.min.js",
        "raphael-min.js",
        "morris.min.js",
        "icheck.min.js",
        "daterangepicker.js",
        "editablegrid.js",
        "editablegrid_renderers.js",
        "editablegrid_editors.js",
        "editablegrid_validators.js",
        "editablegrid_utils.js",
        "chartist.min.js",
        "sweetalert-dev.js",
        "bootstrap-colorpicker.min.js",
        "jquery.easyModal.js",
        "vue-resource.min.js",
        "bootstrap3-typeahead.min.js"
    ], 'public/dist/js/dist.js', 'public/assets/js');

    mix.scripts([
        "jquery-2.1.3.min.js",
        "bootstrap.min.js",
        "icheck.min.js"
    ], 'public/dist/js/login-dist.js', 'public/assets/js');

    mix.copy('public/assets/fonts', 'public/dist/fonts');
    mix.copy('public/assets/img', 'public/dist/img');
    mix.copy('public/dist/homepage', 'public/dist/homepage');
    mix.copy('public/assets/css/square', 'public/dist/css/square');
    mix.copy('public/assets/css/square', 'public/dist/css/square');
    mix.copy('public/dist', '../dist');


});
