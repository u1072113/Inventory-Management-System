<html>
<head>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{asset('dist/css/dist.css')}}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{asset('dist/css/square/blue.css')}}" type="text/css" media="all"/>
    <link rel="shortcut icon" href="{{asset('dist/img/favicon.ico')}}" type="image/x-icon"/>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu+Condensed|Ubuntu' rel='stylesheet' type='text/css'>
    <style>

        .btn-default {
            color: white;
        }

        .content-wrapper {
            background-color: #ffffff !important;
        }

        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 0.5px solid #f4f4f4;
        }

        .form-sidebar {
            padding: 0px;
            margin: 0px !important;
        }

        .form-sidebar label {
            color: white !important;
        }

        .user-panel {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 1px !important;
            padding-bottom: 0px !important;
        }
    </style>

    <title>
        @section('title')
        @show
    </title>
</head>
<body class="{{$theme}}">
@if(env('APP_DEMO',1))
    @include('layouts/analytics')
@endif
@if(env('APP_DEMO',1))
    <div class="bg-yellow"
         style=" z-index:9000; font-size: 12px; position: absolute;float: right; text-align: center; padding: 10px; width: 200px; left: 80%; top: 50px; ">
         <br/>
        <a class="" target="_blank" style="text-align: center;" href="">T</a>
    </div>
@endif
<div class="wrapper">
    @include('layouts/header')
    @include('layouts/sidebar')

    <div class="content-wrapper" id="app">

        <section class="content">
            @if(Session::has('flash_notification.message'))
                <div class="callout callout-{{Session::get('flash_notification.level')}} no-margin flash_message">
                    <b>{{Session::get('flash_notification.message')}}</b>
                </div>
            @endif

            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>University Of Huddersfield</b> 
        </div>
        Copyright &copy;{{date("Y")}} {!!env('COMPANY_NAME')!!}. Created by Robert Calin u1072113
    </footer>
</div>
</body>
<script type="text/javascript" src="{{asset('dist/js/dist.js')}}"></script>
<script>


    $(function () {
        /**
         * Delete Button
         */
        $('.delete-button').click(function () {
            var url = $(this).attr("data-url");
            deleteItem(url);
        });

        function deleteItem(url) {
            swal({
                title: "Are you sure?",
                text: "Are you sure that you want to delete this Item?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes, delete it!",
                confirmButtonColor: "#ec6c62"
            }, function () {
                $.ajax({
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': "{{csrf_token()}}"
                            },
                            type: "DELETE"
                        })
                        .done(function (data) {
                            swal("Deleted!", "Your Item was successfully deleted!", "success");
                            location.reload();
                        })
                        .error(function (data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
            });
        }

        /*** Delete Item END**/

        /**Email Send**/
        $('.send-email').click(function () {
            var url = $(this).attr("data-url");
            swal({
                title: "Send Email Report!",
                text: "Please Enter your email below to send email:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something"
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("Please enter a valid email address!");
                    return false
                }
                $.ajax({
                    url: url,
                    data: {'email': inputValue},
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    type: "GET"
                }).done(function (data) {
                            swal("Email Sent !", "Email Was Sent Successfully!", "success");
                        })
                        .error(function (data) {
                            swal("Oops", "We couldn't send email please check your address", "error");
                        });

            });
        });

        /** Email Send End**/

        /* $('input').iCheck({
         checkboxClass: 'icheckbox_square-blue',
         radioClass: 'iradio_square-blue',
         increaseArea: '20%' // optional
         });*/
        $('.pag').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        $('.pag').on('ifChecked', function (event) {
            console.log(event);
            $.ajax({
                url: '{{action('SettingController@updateAjax',$settingid)}}',
                data: {'paginationDefault': $(this).val()},
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                type: "POST"
            }).done(function (data) {
                        swal({
                            title: "Pagination set !",
                            text: "Pagination settings set Successfully!",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonText: "Reload Page!",
                            closeOnConfirm: false,
                            html: false
                        }, function(){
                            location.reload();
                        });
                       // swal("Pagination set !", "Pagination settings set Successfully!", "success");
                    })
                    .error(function (data) {
                        swal("Oops", "We couldn't set your pagination settings", "error");
                    });

        });
    });

    $(function () {
        // setTimeout() function will be fired after page is loaded
        // it will wait for 5 sec. and then will fire
        // $("#successMessage").hide() function
        setTimeout(function () {
            $(".flash_message").hide("slow")
        }, 5000);
    });

    @if($search)
    $(".search-box").focus();
    @endif
</script>
@include('layouts/js')
@section('popups')

@show

@section('jquery')

@show


</html>