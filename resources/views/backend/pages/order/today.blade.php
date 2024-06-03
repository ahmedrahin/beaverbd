@extends('backend.layout.template')
@section('page-title')
    <title>Manage Today Orders  || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }} </title>
@endsection

@section('page-css')
    <link href="{{asset('backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{asset('backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />   
    <style>
        .alert-danger {
            margin: 0;
        }
        .btn-rounded {
            font-size: 11px;
            font-weight: 800;
            padding: 2px 11px;
        }
        .text-danger{
            font-size: 11px;
            font-weight: 800;
            font-style: italic;
        }
        .user-img {
            object-fit: cover;
        }
       
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
        .btn-group {
            padding: 0 !important;
        }
        a:focus {
            box-shadow: none !important;
        }
        blockquote h4{
            font-size: 13px;
            font-weight: 700;
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
                            <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">{{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }}</a></li>
                                <li class="breadcrumb-item active">Manage Today Orders </li>
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
                                Manage Orders
                                <span class="text-danger">({{date('D-M-y')}})</span>
                                <div class="btn btn-group">
                                    <a href="{{route('manage.order')}}" class="btn btn-primary">All</a>
                                    <a href="{{route('today.order')}}" class="btn btn-primary" style="background: #0c7dc2;">Today</a>
                                    <a href="{{route('month.order')}}" class="btn btn-primary">This Month</a>
                                    <a href="{{route('year.order')}}" class="btn btn-primary">This Year</a>
                                </div>
                            </h4>
                            <div class="data">
                            <div class="table-responsive">
                                @if( $orders->count() == 0 )
                                    <div class="alert alert-danger" role="alert">
                                        No Data Found!
                                    </div>
                                @else
                                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Customer Name</th>
                                                <th>Qty</th>
                                                <th>Total Amount</th>
                                                <th>Order Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($orders as $order)
                                                @php
                                                    $orderItems = App\Models\OrderItem::where('order_id', $order->id)->get();
                                                @endphp
                                                <tr>
                                                    <td>{{$counter++}}</td>
                                                    <td>{{$order->customer_name}}</td>
                                                    <td align="middle">{{$orderItems->sum('quantity')}}</td>
                                                    <td align="middle">{{$order->total_amn}}৳</td>
                                                    <td align="middle">
                                                        @if( $order->order_status == "Pending" )
                                                            <span class="btn btn-secondary btn-rounded waves-effect waves-light">
                                                                {{$order->order_status}}
                                                            </span>
                                                        @elseif( $order->order_status == "Completed" )
                                                            <span class="btn btn-success btn-rounded waves-effect waves-light">
                                                                {{$order->order_status}}
                                                            </span>
                                                        @elseif( $order->order_status == "Processing" )
                                                            <span class="btn btn-info btn-rounded waves-effect waves-light">
                                                                {{$order->order_status}}
                                                            </span>
                                                        @elseif( $order->order_status == "Cancel" )
                                                            <span class="btn btn-danger btn-rounded waves-effect waves-light">
                                                                {{$order->order_status}}
                                                            </span>
                                                        @elseif( $order->order_status == "Partially Refunded" )
                                                            <span class="btn btn-warning btn-rounded waves-effect waves-light">
                                                                {{$order->order_status}}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="action">
                                                        <button>
                                                            <a href="{{route('order.invoice',$order->id)}}" target="_blank">
                                                                <i class="ri-printer-fill"></i>
                                                            </a>
                                                        </button>
                                                        <button>
                                                            <a href="{{route('details.order',$order->id)}}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        </button>
                                                        <button class="deleteButton" data-order-id="{{ $order->id }}">
                                                            <i class="ri-delete-bin-2-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="row exp_info">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Today Orders Report
                            </div>
                            <div class="card-body">
                                <blockquote class="card-blockquote mb-0">
                                    <h4>Total Orders:
                                        <span class="text-danger" style="float: right;">
                                            @php
                                                $totalOrders = $orders->count();
                                                echo $totalOrders;
                                            @endphp
                                        </span>
                                    </h4>
                                    <hr>
                                    <h4>Total Orders Amount:
                                        <span class="text-danger" style="float: right;">
                                            @php
                                                $orderAmount = $orders->sum('total_amn');
                                                echo $orderAmount . "৳";
                                            @endphp
                                        </span>
                                    </h4>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div> 
    </div>
    <!-- End Page-content -->
                
@endsection

@section('page-script')
    {{-- delete product --}}
    <script>
        $(document).ready(function() {
            $('.deleteButton').click(function() {
                var deleteButton = $(this); 
                
                var id = deleteButton.data('order-id');

                // Trigger SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this order data!',
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
                            url: '/admin/order/delete/' + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {    
                                // Remove the row from the table
                                deleteButton.closest('tr').fadeOut('slow', function() {
                                    $(this).remove();
                                });

                                setTimeout(() => {
                                    Swal.fire('Deleted!', 'The Order has been deleted.', 'success');
                                }, 1000);

                            },
                            error: function(xhr, textStatus, errorThrown) {
                                // Handle deletion error
                                Swal.fire('Error!', 'Failed to delete Order.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    <!-- Responsive examples -->
    <script src="{{asset('backend/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Datatable init js -->
    <script src="{{asset('backend/js/pages/datatables.init.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('backend/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('backend/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('backend/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    <script src="{{asset('backend/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>


@endsection