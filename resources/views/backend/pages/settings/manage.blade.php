@extends('backend.layout.template')
@section('page-title')
    <title>Genarel Settings || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </title>
@endsection

@section('page-css')
   
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
                                <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">{{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </a></li>
                                <li class="breadcrumb-item active">Genarel Settings</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- 64 of bandladesh cities --}}
            @php
                $cities = [
                    "Dhaka", "Chattogram", "Khulna", "Sylhet", "Rajshahi", "Comilla", "Mymensingh", "Barishal", "Rangpur",
                    "Narayanganj", "Gazipur", "Narayanganj", "Tangail", "Jessore", "Cox's Bazar", "Dinajpur", "Jamalpur",
                    "Bogra", "Kushtia", "Nawabganj", "Rangamati", "Narsingdi", "Bagerhat", "Pabna", "Sirajganj",
                    "Narail", "Moulvibazar", "Gopalganj", "Netrokona", "Faridpur", "Joypurhat", "Bhairab", "Feni", "Saidpur",
                    "Brahmanbaria", "Azimpur", "Thakurgaon", "Manikganj", "Bandarban", "Pirojpur", "Lakshmipur", "Kishoreganj",
                    "Chuadanga", "Sherpur", "Satkhira", "Chandpur", "Meherpur", "Jhalokati", "Natore", "Bagerhat", "Bhola",
                    "Chapainawabganj", "Patuakhali", "Nilphamari", "Lalmonirhat", "Kurigram", "Madaripur", "Sunamganj", "Gaibandha",
                    "Magura", "Khagrachari", "Panchagarh"
                ];
            @endphp
            
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Genarel Settings</h4>

                            <form action="{{route('update.settings')}}" method="POST" class="needs-validation"  novalidate enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="validationName" class="form-label">Company Name</label>
                                            <input type="text" class="form-control" id="validationName" placeholder="Company Name" name="name" value="{{$setting->company_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="validationEamil" class="form-label">Eamil</label>
                                            <input type="email" class="form-control" id="validationEamil" placeholder="Enter Email" name="email" value="{{$setting->email}}" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone No.</label>
                                            <input type="text" id="phone" class="form-control" placeholder="Phone No." name="phone" value="{{$setting->phone}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" placeholder="Enter Shop Addrss" name="address" value="{{$setting->address}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">City / Zip</label>
                                            <select name="city" class="form-control select2">
                                                <option value="">Select City</option>
                                                <?php foreach ($cities as $city): ?>
                                                    <option value="<?php echo $city; ?>" {{($setting->city == $city)? 'selected' : ''}} ><?php echo $city; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="zip" class="form-label">Zip Code</label>
                                            <input type="text" id="zip" class="form-control" placeholder="Zip Code" name="zip" value="{{$setting->zip}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                       <div class="mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" name="logo" id="logo" class="form-control">
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                             <label for="fav" class="form-label">Fav Icon</label>
                                             <input type="file" name="fav" id="fav" class="form-control">
                                        </div>
                                     </div>
                                </div>
                                
                                <div>
                                    <button class="btn btn-primary" type="submit" id="saveChange"> Save Changes </button>
                                </div>
                            </form>
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
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    {{-- select box --}}
    <script src="{{asset('backend/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('backend/js/pages/form-advanced.init.js')}}"></script>

    {{-- send data --}}
    <script>

        $('#addEmployee').prop('disabled', true);

        // Enable the submit button when any input field changes
        $('.needs-validation input').on('input', function() {
            $('#addEmployee').prop('disabled', false);
        });

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
                        $("#saveChange").prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        `);
                    },
                    success: function(response) {
                        $("#saveChange").prop('disabled', true).html(`
                            Save Changes
                        `);

                        // Display SweetAlert popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Changed Successfully',
                        });

                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Reset Bootstrap validation state
                        form.find('.form-control').removeClass('is-invalid');
                        form.find('.invalid-feedback').html('');
                        $("#saveChange").prop('disabled', false).html('Save Changes');
                        
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
            $('.needs-validationP input').on('input', function() {
                var input = $(this);
                input.removeClass('is-invalid');
                input.next('.invalid-feedback').html('');
            });
        });
    </script>

@endsection