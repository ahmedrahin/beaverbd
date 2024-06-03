@extends('backend.layout.template')
@section('page-title')
    <title>Shipping Method || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </title>
@endsection

@section('page-css')
    <link href="{{asset('backend/libs/select2/css/select2.min.css')}}" rel="stylesheet">
    <style>
        .card-title .btn-primary {
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 600;
        }
        .card-title {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
        }
        .card-title i {
            padding-right: 2px;
        }
        input[type="checkbox"]{
            opacity: 0;
        }
        input[switch]:checked+label:after {
            left: 52px !important;
        }
        input[switch]+label:after {
            height: 17px !important;
            width: 17px !important;
            top: 3px !important;
            left: 4px !important;
        }
        input[switch]+label{
            width: 73px !important;
            height: 24px !important;
            margin-bottom: 0;
        }
    </style>
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
                                <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">{{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </a></li>
                                <li class="breadcrumb-item active">Shipping</li>
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
                                Shipping Method
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#shipping"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Shipping</button>
                            </h4>

                            <div id="data">
                                @include('backend.pages.shipping.details')
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

    <!-- add shipping -->

    <div class="modal fade" id="shipping" aria-hidden="true" aria-labelledby="..." tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <form action="{{route('add.shipping')}}" method="post" class="needs-validation" novalidate>
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Shipping Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Shipping Name">
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="charge">Shipping Charge</label>
                                    <input type="text" name="charge" id="charge" class="form-control" placeholder="00.00">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="modal-footer">
                                    <!-- Toogle to second dialog -->
                                    <button class="btn btn-primary" type="submit" id="addShipping">Add Shipping</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                
@endsection

@section('page-script')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{asset('backend/js/pages/form-advanced.init.js')}}"></script>

    {{-- add shipping data --}}
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
                    contentType: false, // Don't set content type
                    processData: false, // Don't process the data
                    beforeSend: function(){
                        $("#addShipping").prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        `);
                    },
                    success: function(response) {
                        $("#addShipping").prop('disabled', false).html(`
                            Add Shipping
                        `);
                        $('.needs-validation')[0].reset();
                        $('.needs-validation').find('.form-control').removeClass('form-control');
                        // Display SweetAlert popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Shipping added successfully!',
                        });

                        $('.btn-close').click();
                        $('#data').html(response.html);
                        deleteShipping();
                        changeStatus();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Reset Bootstrap validation state
                        form.find('.form-control').removeClass('is-invalid');
                        form.find('.invalid-feedback').html('');
                        $("#addShipping").prop('disabled', false).html('Add Shipping');
                        
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

            // delete shipping 
           function deleteShipping(){
                $('.deleteButton').click(function() {
                    var deleteButton = $(this); 
                    
                    var id = deleteButton.data('shipping-id');

                    // Trigger SweetAlert confirmation dialog
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this shipping data!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                    }).then((result) => {
                        // Handle the user's response
                        if (result.isConfirmed) {
                            // Send an AJAX request to delete the product
                            $.ajax({
                                type: 'DELETE',
                                url: '/admin/shipping/delete/' + id,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {    
                                    // Remove the row from the table
                                    deleteButton.closest('tr').fadeOut('slow', function() {
                                        $(this).remove();
                                    });
                                    
                                    setTimeout(() => {
                                        Swal.fire('Deleted!', 'Shipping has been deleted.', 'success');
                                    }, 1000);

                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    // Handle deletion error
                                    Swal.fire('Error!', 'Failed to delete shipping.', 'error');
                                }
                            });
                        }
                    });
                });
            }

            // change status
            function changeStatus(){
                
                $('.status-toggle').change(function() {
                    
                    var id = $(this).data('shipping-id');
                    var status = $(this).prop('checked') ? 1 : 0;
                
                    // Send AJAX request
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/shipping/update-status/' + id,
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function(response) {
                            // Handle success response here
                            console.log(response);
                            Swal.fire({
                                icon: response.type,
                                title: response.msg,
                                text: ''
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle error here
                            console.error(xhr.responseText);
                        }
                    });
                });
            }

            deleteShipping();
            changeStatus()
        });
    </script>

@endsection