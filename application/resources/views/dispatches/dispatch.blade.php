@extends('layouts.master')

@section('title')
    Dispatch Item
@endsection


@section('content')
    @include('dispatches.form')

@endsection

@section('js')

@endsection

@section('jquery')
    <script>
        var app = new Vue({
            el: '#app',
            http: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            ready: function () {
                $('.catSelect').select2({
                    allowClear: true,
                    placeholder: "Please Select ",
                });
                $('.depSelect').select2({
                    allowClear: true,
                    placeholder: "Please Select ",
                });
                $('.dispselect').select2({
                    allowClear: true,
                    placeholder: "Please Select ",
                });
                $('.easy-modal').easyModal({
                    top: 100,
                    overlay: 1,
                    overlayOpacity: 0.3,
                    overlayColor: "#333",
                    overlayClose: false,
                    closeOnEscape: false,
                    updateZIndexOnOpen: false
                });

                this.$http.get('{{url('staff/get/all')}}', function (returndata, status, request) {

                    console.log(JSON.stringify(returndata));
                    this.$set('selectData', returndata);
                    $('.catSelect').select2({data: returndata});

                }).error(function (data, status, request) {
                    // handle error
                })

            },

            data: {

                name: '',
                email: '',
                deparmentId: [],
                selectData: [],
            },
            methods: {
                openModal: function (event) {
                    $("#modal1").trigger('openModal');
                },
                closeModal: function (event) {
                    $("#modal1").trigger('closeModal');
                    this.name = "";
                    this.email = "";
                },
                addCategory: function (event) {
                    var data = {
                        name: this.name,
                        email: this.email,
                        departmentId: this.departmentId,
                    };
                    // GET request
                    this.$http.post('{{url('staff/create/ajax')}}', data, function (returndata, status, request) {
                        this.selectData.push(returndata);
                        $('.catSelect').select2({data: this.selectData});
                        $("#modal1").trigger('closeModal');
                        $('.catSelect').val(returndata.id).trigger("change");
                        this.name = "";
                        this.email = "";

                    }).error(function (data, status, request) {
                        // handle error
                    })
                },
                setCategory: function (event) {
                    alert(event);
                }
            }
        })

        $('.depSelect').on("select2:select", function (e) {
            //e.params.data.id;
            console.log(e.params.data.id);
            app.departmentId = e.params.data.id;
        });


    </script>
@endsection