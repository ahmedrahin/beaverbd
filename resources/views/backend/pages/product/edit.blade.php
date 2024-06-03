@extends('backend.layout.template')
@section('page-title')
    <title>Edit {{$editData->title}} || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </title>
@endsection

@section('page-css')
    <style>
        .AppBody {
            border: 3px dotted #d1d6d6;
            height: 240px;
            width: 80%;
            background-color: #fff;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
        }
        .AppBody.active {
            border: 3px solid #0f9cf3;
        }
        .icon {
            font-size: 50px;
            color: #0f9cf3;
        }
        .AppBody h3 {
            font-size: 23px;
            font-weight: 600;
            color: #333;
        }
        .AppBody span {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin: 6px 0 2px 0;
        }
        .AppBody button {
            padding: 10px 25px;
            font-size: 20px;
            font-weight: 500;
            border: none;
            outline: none;
            background: #fff;
            color: #0f9cf3;
            border-radius: 5px;
            cursor: pointer;    
        }
        .AppBody img{
            height: 100%;
            width: 100%;
            object-fit: cover;
            border-radius: 5px;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 11;
        }
        .cancell {
            font-weight: 800;
            font-size: 11px;
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            z-index: 12;
        }
        .card-title {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
        }
        .card-title .btn-primary {
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
    <link href="{{asset('backend/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('backend/libs/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('body-content')

    <!-- Start Page-content -->
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                        <div class="page-title">
                            <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">{{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }}</a></li>
                                <li class="breadcrumb-item active">Edit {{$editData->title}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                Edit Product
                                <a href="{{route('manage.product')}}" class="btn btn-primary">All Product</a>
                            </h4>
                            {{-- edit data --}}
                            <div id="edit-data">
                                <form action="{{route('update.product', $editData->id)}}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="validationName" class="form-label">Prodcut Title</label>
                                            <input type="text" class="form-control" id="validationName" placeholder="Product Title" name="title" value="{{ $editData->title }}" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Sku Code</label>
                                            <input type="text" class="form-control" id="code" placeholder="Sku Code" name="code" value="{{ $editData->sku_code }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control" id="price" placeholder="Product Price" name="price" required value="{{ $editData->price }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="offer_price" class="form-label">Offer Price</label>
                                            <input type="text" class="form-control" id="offer_price" placeholder="Offer Price" name="offer_price" value="{{ $editData->offer_price }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
    
                                    <div class="row">
                                        <div class="col-md-5">
                                           <div class="mb-3">
                                                <label for="salary" class="form-label">Product Thumbnail</label>
                                                <div class="AppBody">
                                                    <div class="icon">
                                                        <i class="fas fa-images"></i>
                                                    </div>
                                            
                                                    <h3 class="drag">Drag & Drop</h3>
                                                    <span>OR</span>
                                                    <button type="button" id="browseFile">Browse File</button>
                                                    <input type="file" name="image" class="picture" hidden>

                                                    {{-- has image --}}
                                                    @if( !is_null($editData->image) )
                                                        <img src="{{asset($editData->image)}}" alt="" id="editImg">
                                                        <div class="cancell" id="editCan">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </div>
                                                        <input type="hidden" name="hasRemove" id="hasRemove">
                                                    @endif
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="status" value="{{$editData->status}}">
                                    <div>
                                        <button class="btn btn-primary" type="submit" id="editProduct"> Save Changes </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div> 
            </div>
            <!-- end row -->

        </div> 
    </div>
    <!-- End Page-content -->
                
@endsection

@section('page-script')
    <script src="{{asset('backend/js/pages/form-validation.init.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    {{-- data picker --}}
    <script src="{{asset('backend/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    {{-- select box --}}
    <script src="{{asset('backend/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('backend/js/pages/form-advanced.init.js')}}"></script>

    {{-- send Supplier data --}}
    <script>
        $(document).ready(function() {
            $('.needs-validation').submit(function(event) {
                event.preventDefault(); 
                var form = $(this);
                var formData = new FormData(form[0]); 

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    contentType: false, 
                    processData: false,
                    beforeSend: function(){
                        $("#editProduct").prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        `);
                    },
                    success: function(response) {
                        $("#editProduct").prop('disabled', false).html(`
                            Save Changes
                        `);
                        

                        // Display SweetAlert popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Product Infomation Updated!',
                        });

                        imgUpload();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Reset Bootstrap validation state
                        form.find('.form-control').removeClass('is-invalid');
                        form.find('.invalid-feedback').html('');
                        $("#editProduct").prop('disabled', false).html('Save Changes');
                        
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        console.log(errors);
                        $.each(errors, function(key, value) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.addClass('form-control');
                            input.next('.invalid-feedback').html(value);
                        });
                    }
                });
            });

            // Remove validation classes and messages on input change
            $('.needs-validation input').on('input', function() {
                var input = $(this);
                input.removeClass('is-invalid');
                input.next('.invalid-feedback').html('');
            });
        });
    </script>

    {{-- drag & drop --}}
    <script>
        function imgUpload() {
            let dragArea = document.querySelector('.AppBody');
            let dragText = document.querySelector('.drag');
            let btn = document.querySelector('#browseFile');
            let input = document.querySelector('.picture');
            let file;

            btn.onclick = () => {
                input.click();
            }

            input.addEventListener('change', function () {
                file = this.files[0];
                show();
            })

            dragArea.addEventListener('dragover', (event) => {
                event.preventDefault();
                dragText.innerText = "Release to Upload File";
                dragArea.classList.add('active');
            })

            dragArea.addEventListener('dragleave', (event) => {
                dragText.innerText = "Drag & Drop";
                dragArea.classList.remove('active');
            })

            dragArea.addEventListener('drop', (event) => {
                event.preventDefault();
                file = event.dataTransfer.files[0];
                input.files = event.dataTransfer.files; // Set files to input
                show();
            })

            function show() {
                let fileType = file.type;
                let validType = ['image/jpeg', 'image/jpg', 'image/png'];

                if (validType.includes(fileType)) {
                    let fileRead = new FileReader();
                    fileRead.onload = () => {
                        let imgUrl = fileRead.result;
                        let img = `<img src="${imgUrl}">`;
                        let cancelButton = `<div class="cancell">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </div>`;
                        // Create a new div for the uploaded image and cancel button
                        let imageContainer = document.createElement('div');
                        imageContainer.classList.add('image-container');
                        imageContainer.innerHTML = img + cancelButton;

                        // Check if an image is already uploaded
                        let existingImageContainer = dragArea.querySelector('.image-container');
                        if (existingImageContainer) {
                            // Remove the existing image container
                            dragArea.removeChild(existingImageContainer);
                        }
                        dragArea.appendChild(imageContainer);

                        // Add event listener to the cancel button
                        let cancelButtonElement = imageContainer.querySelector('.cancell');
                        cancelButtonElement.addEventListener('click', function () {
                            // Clear the input file and remove the image container
                            input.value = null;
                            dragArea.classList.remove('active');
                            dragText.innerText = "Drag & Drop";
                            dragArea.removeChild(imageContainer);
                        });
                    }
                    fileRead.readAsDataURL(file);
                } else {
                    alert('This file is not valid');
                    dragArea.classList.remove('active');
                    dragText.innerText = "Drag & Drop";
                }
            }
                $('#editCan').on('click', function(){
                $('#editImg').remove();
                $('#hasRemove').val(1)
                $(this).remove();
                imgUpload();
            })
        }

        imgUpload();
    </script>

@endsection