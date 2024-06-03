@extends('backend.layout.template')
@section('page-title')
    <title>Order Detials || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : '' }} </title>
@endsection

@section('page-css')
    {{-- custom --}}
    <style>
        .shippingnotfound {
            font-size: 12px;
            font-weight: 800;
            font-style: italic;
        }
        .card-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        h5 {
            font-size: 16px !important;
            font-weight: 800 !important;
        }
        .customer th {
            background: #252b3be6;
            color: white;
        }
        .p_d {
            background: #252b3be6 !important;
            color: white;
        }
        .customer {
            background: #f7f6f6;
        }
        .btn-danger {
            padding: 1px 5px;
            font-size: 11px;
            font-weight: 800;
        }
        .card-body.status {
            border: 1px solid #e4e5e6;
            border-top: 0;
        }
        .variation {
            display: block;
            font-size: 10px;
        }
    </style>
    <!-- Responsive datatable examples -->
    <link href="{{asset('backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />   
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
                                <li class="breadcrumb-item active">Order Detials</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">
                                Order's Detials
                                <a href="{{route('manage.order')}}" class="btn btn-primary waves-effect waves-light">
                                    All Orders <i class="ri-arrow-right-line align-middle ms-2"></i> 
                                </a>
                            </h4>

                            <div class="row">
                                <div class="col-md-5 col-lg-5">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="information-box mb-3">
                                                <div class="table-responsive order-details">
                                                    <h5 class="h5">Customer Details</h5>
                                                    <table class="table table-bordered customer">
                                                        <thead class="">
                                                            <tr>
                                                                <th>Customer Name</th>
                                                                <td>{{$details->customer_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Customer Phone</th>
                                                                <td>{{$details->phone}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Customer Address</th>
                                                                <td>{{$details->address}}</td>
                                                            </tr>
                                                        
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7 col-lg-7">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="information-box mb-3">
                                                <div class="table-responsive order-details">
                                                    <h5 class="h5">Product Details</h5>
                                                    <table class="table table-bordered">
                                                        <thead class="p_d">
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Product Title</th>
                                                                <th>Unit Price</th>
                                                                <th style="text-align: center;">Qty</th>
                                                                <th>Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $totalQuantity = 0;
                                                                $totalDiscount = 0;
                                                                $totalSubtotal = 0;
                                                    
                                                                // Calculate the total quantity of items in the cart
                                                                foreach ($items as $item) {
                                                                    $totalQuantity += $item->quantity;
                                                                }
                                                    
                                                                // Determine the discount per item based on the total quantity
                                                                $discountPerItem = 0;
                                                                if ($totalQuantity == 3) {
                                                                    $discountPerItem = 50;
                                                                }
                                                            @endphp
                                                    
                                                            @foreach( $items as $item )
                                                                @php
                                                                    $productSubtotal = $item->price * $item->quantity;
                                                                    $discount = $item->quantity * $discountPerItem;
                                                                    $totalDiscount += $discount;
                                                                    $totalSubtotal += $productSubtotal - $discount;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        @if( !is_null($item->product->image) )
                                                                            <img src="{{ asset($item->product->image) }}" alt="" class="user-img">
                                                                        @else
                                                                            <img src="{{ asset('backend/images/default.jpg') }}" alt="" class="user-img">
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span style="color:black">{{ $item->product->title }}</span>
                                                                        <span class="variation">
                                                                            @if( !is_null($item->size) && !is_null($item->color) )
                                                                                (size: {{ $item->size }} | color: {{ $item->color }} )
                                                                            @elseif( !is_null($item->size) )
                                                                                (size: {{ $item->size }})
                                                                            @elseif( !is_null($item->color) )
                                                                                (color: {{ $item->color }})
                                                                            @endif
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ $item->price }}৳</td>
                                                                    <td align="middle">{{ $item->quantity }}</td>
                                                                    <td>{{ $productSubtotal - $discount }}৳</td>
                                                                </tr>
                                                            @endforeach
                                                    
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <th>Discount (-)</th>
                                                                <td>{{ $totalDiscount }}৳</td>
                                                            </tr>
                                                    
                                                            <tr style="background: #F7F6F6;">
                                                                <td colspan="3"></td>
                                                                <th>Shipping Charge</th>
                                                                <td>
                                                                    @if( isset( $details->shipping->id ) )
                                                                        {{ number_format($details->shipping->charge, 2) }}৳
                                                                    @else
                                                                    00.00৳
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <th>Total =</th>
                                                                <th>{{ number_format($totalSubtotal + (isset($details->shipping->charge) ? $details->shipping->charge : 0), 2) }}
৳</th>
                                                            </tr>
                                                        </tbody>
                                                    </table>


                                                    </div>
                                            </div>
                                        </div>
    
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <h5 class="h5"></h5>
                                    
                                    <div class="card-header" style="font-size: 15px;font-weight: 700;">
                                        Update Order Status
                                    </div>
                                    <div class="card-body status">
                                        <blockquote class="card-blockquote mb-0">
                                            <form action="{{route('update.status', $details->id)}}" method="post">
                                                @csrf
                                                <select name="order_status" class="form-control select2">
                                                    <option value="Pending" {{( $details->order_status == "Pending" ) ? "selected" : ""}} >Pending</option>
                                                    <option value="Processing" {{( $details->order_status == "Processing" ) ? "selected" : ""}} >Processing</option>
                                                    <option value="Completed" {{( $details->order_status == "Completed" ) ? "selected" : ""}} >Completed</option>
                                                    <option value="Cancel" {{( $details->order_status == "Cancel" ) ? "selected" : ""}} >Cancel</option>
                                                    <option value="Partially Refunded" {{( $details->order_status == "Partially Refunded" ) ? "selected" : ""}} >Partially Refunded</option>
                                                </select>
                                                <button class="btn btn-primary" style="margin-top: 15px;" id="changeStatus">Save Changes</button>
                                            </form>
                                        </blockquote>
                                    </div>
                                </div>

                                <div class="col-md-7 col-lg-7">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="information-box mb-3">
                                                <div class="table-responsive order-details">
                                                    <h5 class="h5"> Order Details </h5>
                                                         <table class="table table-bordered">
                                                            <thead class="p_d">
                                                                <tr>
                                                                    <th>Order Id</th>
                                                                    <th>Order Date</th>
                                                                    <th>Order Time</th>
                                                                    <th>Shipping</th>
                                                                    <th>Payment Method</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                               
                                                                <tr>
                                                                    <td>#{{$details->id}}</td>
                                                                    <td>
                                                                        {{$details->order_date}}
                                                                    </td>
                                                                    <td>
                                                                    @php
                                                                            $messageTime = Carbon\Carbon::parse($details->created_at);
                                                                            $timeDiff = $messageTime->diffInSeconds();
                                                                        @endphp

                                                                        @if ($timeDiff < 60)
                                                                            {{ $timeDiff . ' sec ago' }}
                                                                        @elseif ($timeDiff < 3600)
                                                                            {{ $messageTime->diffInMinutes() . ' min ago' }}
                                                                        @elseif ($timeDiff < 86400)
                                                                            {{ $messageTime->diffInHours() . ' hr ago' }}
                                                                        @elseif ($timeDiff < 31536000)
                                                                            {{ $messageTime->diffInDays() . ' days ago' }}
                                                                        @else
                                                                            {{ $messageTime->diffInYears() . ' years ago' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if( $details->shipping_id != 'free' )
                                                                            @if( isset( $details->shipping->id ) )
                                                                                {{$details->shipping->shipping_name}} {{$details->shipping->charge}}৳
                                                                                @else
                                                                                <span class="text-danger shippingnotfound">Shipping is not found</span>
                                                                            @endif
                                                                        @else
                                                                        <span class="text-success shippingnotfound">Free Shipping</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        Cash on delivery
                                                                    </td>
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>

            
        </div> 
    </div>
    <!-- End Page-content -->
                
@endsection

@section('page-script')

    {{-- change order status --}}
    <script>
        $(document).ready(function(){
            $('#changeStatus').click(function(event) {
                event.preventDefault(); 
                
                var submitButton = $(this); 
                var form = submitButton.closest('form');
                
                $.ajax({
                    type: form.attr('method'), 
                    url: form.attr('action'),  
                    data: form.serialize(),   
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function(){
                        submitButton.prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        `);
                    },
                    success: function(response) {    
                        submitButton.prop('disabled', false).html(`
                            Save Changes
                        `);
                        Swal.fire('Success!', 'Order Status is Changed', 'success');
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        submitButton.prop('disabled', false).html(`
                            Save Changes
                        `);
                        // Handle deletion error
                        Swal.fire('Error!', 'Failed', 'error');
                    }
                });
            });

        })
    </script>

    {{-- select box --}}
    <script src="{{asset('backend/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('backend/js/pages/form-advanced.init.js')}}"></script>

@endsection