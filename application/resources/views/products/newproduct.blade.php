@extends('layouts.master')
@section('title')
    Add New Item to Inventory
@endsection

@section('content')
    @include('products.form')
    <div class="easy-modal" id="modal1">
        <div class="header modal-header text-center">
            <h3>Add A new Category</h3>
        </div>
        <div class="easy-modal-inner">
            <div class="form-group">
                <input type="text" class="form-control" v-model="categoryName" placeholder="Category Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" v-model="categoryDescription"
                       placeholder="Category Description">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" v-on:click="addCategory"
                            v-show="categoryName.length > 2 && categoryDescription.length > 2"
                            class="btn btn-flat bg-green btn-block">Add Category
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-flat bg-red btn-block" v-on:click="closeModal">Cancel</button>
                </div>
            </div>


        </div>
    </div>
@endsection



@section('js')
    $('#expirationDate').datepicker({
    format:"yyyy/mm/dd"
    });


    // $("#image").dropzone({ });

    Dropzone.options.image = {
    maxFiles: 1,
    url: "{!! url('/product/upload/photo') !!}",
    paramName: "file",
    dictDefaultMessage: "Upload your Stock Item Image here",
    acceptedFiles: "image/*",
    headers: {
    "X-CSRF-Token": $('input[name="_token"]').val()
    },
    uploadprogress: function (progress, bytesSent) {
    console.log(progress);
    },
    success:function(file,response){
    console.log(response.save_path);
    $('input[name="productImage"]').val(response.save_path);
    },
    maxfilesexceeded: function(file) {
    this.removeAllFiles();
    this.addFile(file);
    }
    };
@endsection

@section('jquery')

    <script>

        $('.products').typeahead({source: {!!  $products!!} });
        $('.serial').typeahead({source: {!!  $serials!!} });
        var app = new Vue({
            el: '#app',
            http: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            ready: function () {
                $('.catSelect').select2({
                    allowClear: true
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

                this.$http.get('{{url('product/category/get')}}', function (returndata, status, request) {

                    console.log(JSON.stringify(returndata));
                    this.$set('selectData', returndata);
                    $('.catSelect').select2({data: returndata});
                    @if(isset($product))
                    $('.catSelect').val('{{$product->categoryId}}').trigger('change')
                    @else
                    $('.catSelect').val('').trigger('change')
                    @endif

                }).error(function (data, status, request) {
                    // handle error
                })

            },

            data: {

                categoryName: '',
                categoryDescription: '',
                selectData: [],
                categoryNamePost: ''
            },
            methods: {
                openModal: function (event) {
                    $("#modal1").trigger('openModal');
                },
                closeModal: function (event) {
                    $("#modal1").trigger('closeModal');
                },
                addCategory: function (event) {
                    var data = {
                        categoryName: this.categoryName,
                        categoryDescription: this.categoryDescription,
                    };
                    // GET request
                    this.$http.post('{{url('product/category/add')}}', data, function (returndata, status, request) {
                        this.selectData.push(returndata);
                        $('.catSelect').select2({data: this.selectData});
                        $("#modal1").trigger('closeModal');
                        $('.catSelect').val(returndata.id).trigger("change");
                        this.categoryNamePost = returndata.text;


                    }).error(function (data, status, request) {
                        // handle error
                    })
                },
                setCategory: function (event) {
                    alert(event);
                }
            }
        })

        $('.catSelect').on("select2:select", function (e) {
            //e.params.data.id;
            app.categoryNamePost = e.params.data.text;
        });

        $('.catSelect').on("select2:change", function (e) {
            //e.params.data.id;
            app.categoryNamePost = e.params.data.text;
        });


    </script>
@endsection