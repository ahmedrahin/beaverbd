@extends('backend.layout.template')
@section('page-title')
    <title>Order Invoice || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : '' }} </title>
@endsection

@section('page-css')
    {{-- custom --}}
    <style type="text/css">
        address {
            margin: 0 !important;
        }
        .variation {
            display: block;
            font-size: 10px;
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
                                <li class="breadcrumb-item active">Invoice</li>
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
                                Order Invoice
                            </h4>

                            <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="invoice-title">
                                                    <h4 class="float-end font-size-16"><strong>Order #{{$order_invoice->id}}</strong></h4>
                                                    <h3>
                                                        <!--favicon-->
                                                        @php
                                                            $favIcon = \App\Models\Settings::shop_fav();
                                                        @endphp
                                                        @if(!is_null($favIcon))
                                                            <img src="{{ asset($favIcon->fav_icon) }}" alt="logo" height="24"/>
                                                        @endif
                                                    </h3>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <address>
                                                            <strong>Billed To:</strong><br>
                                                            {{$order_invoice->customer_name}}<br>
                                                            {{$order_invoice->phone}}<br>
                                                            {{$order_invoice->address}}<br>
                                                        </address>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <address>
                                                            <strong>Shipped To:</strong><br>
                                                            {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : ''}}<br>

                                                            {{ !is_null($shop_email = App\Models\Settings::shop_email()) ? $shop_email->email: ''}}<br>

                                                            {{ !is_null($call_1 = App\Models\Settings::call_1()) ? $call_1->phone1  : ''}}<br>

                                                            {{ !is_null($shop_address = App\Models\Settings::shop_address()) ? $shop_address->address : ''}}<br>

                                                            {{ !is_null($city = App\Models\Settings::city()) ? $city->city . ', ' . $city->zip : '' }}<br>
                                                        </address>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mt-4">
                                                        <address>
                                                            <strong>Payment Method:</strong><br>
                                                            ক্যাশ অন ডেলিভারি
                                                            <br>

                                                            @if( !is_null($order_invoice->order_status) )
                                                            <strong>Order Status:</strong>
                                                                <br> {{$order_invoice->order_status}}
                                                                
                                                            @endif
                                                        </address>
                                                    </div>
                                                    <div class="col-6 mt-4 text-end">
                                                        <address>
                                                            <strong>Order Date:</strong><br>
                                                            @if( !is_null($order_invoice->order_date) )
                                                                {{$order_invoice->order_date}}
                                                                <br>
                                                            @endif

                                                            @if( isset( $order_invoice->shipping->id ) )
                                                                <strong>Shipping Method:</strong>
                                                                <br>
                                                                {{$order_invoice->shipping->shipping_name}} {{$order_invoice->shipping->charge}}৳
                                                            @endif
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
        
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <div class="p-2">
                                                        <h3 class="font-size-16"><strong>Order summary</strong></h3>
                                                    </div>
                                                    <div class="">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <td><strong>Sl.</strong></td>
                                                                    <td><strong>Image</strong></td>
                                                                    <td><strong>Product Title</strong></td>
                                                                    <td class="text-center"><strong>Price</strong></td>
                                                                    <td class="text-center"><strong>Quantity</strong>
                                                                    </td>
                                                                    <td class="text-end"><strong>Subtotal</strong></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                                    <?php
                                                                        $count = 1;
                                                                    ?>

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
                                                                            $discountPerItem = 50; // Special discount for exactly 3 items
                                                                        } elseif ($totalQuantity > 3) {
                                                                            $discountPerItem = 50;
                                                                        } elseif ($totalQuantity > 1) {
                                                                            $discountPerItem = 25;
                                                                        }
                                                                    @endphp
                                                                    @foreach( $items as $item )
                                                                        @php
                                                                            $productSubtotal = $item->price * $item->quantity;
                                                                            $discount = 0;
                                        
                                                                            if ($totalQuantity == 3) {
                                                                                // If total quantity is exactly 3, set total price to 1490 distributed across items
                                                                                $discount = $productSubtotal - (1490 / $totalQuantity * $item->quantity);
                                                                            } elseif ($totalQuantity > 3) {
                                                                                $discount = $item->quantity * $discountPerItem;
                                                                            } elseif ($totalQuantity > 1) {
                                                                                $discount = $item->quantity * $discountPerItem;
                                                                            }
                                        
                                                                            $totalDiscount += $discount;
                                                                            $totalSubtotal += $productSubtotal - $discount;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{$count++}}</td>
                                                                            <td>
                                                                                @if( !is_null($item->product->image) )
                                                                                    <img src="{{asset($item->product->image)}}" alt="" class="user-img">
                                                                                @else
                                                                                    <img src="{{asset('backend/images/default.jpg')}}" alt="" class="user-img">
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{$item->product->title}}
                                                                                <span class="variation">
                                                                                    @if( !is_null($item->size) )
                                                                                        (size: {{ $item->size }} )
                                                                                    @endif
                                                                                </span>
                                                                            </td>
                                                                            <td class="text-center">{{ number_format($item->price, 2) }}৳</td>
                                                                            <td class="text-center">{{$item->quantity}}</td>
                                                                            <td class="text-end">
                                                                                {{ number_format($productSubtotal - $discount, 2) }}৳
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                    @endforeach
                                                                    <tr align="right">
                                                                        <td colspan="4"></td>
                                                                        <th>Discount (-)</th>
                                                                        <td>{{ number_format($totalDiscount, 2) }}৳</td>
                                                                    </tr>
                                                            
                                                                    <tr align="right">
                                                                        <td colspan="4"></td>
                                                                        <th>Shipping Charge</th>
                                                                        <td>
                                                                        @if( isset( $order_invoice->shipping->id ) )
                                                                            {{ number_format($order_invoice->shipping->charge, 2) }}৳
                                                                        @else
                                                                        00.00৳
                                                                        @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr align="right">
                                                                        <td colspan="4"></td>
                                                                        <th>Total =</th>
                                                                        <th>{{ number_format($totalSubtotal + (isset($order_invoice->shipping->charge) ? $order_invoice->shipping->charge : 0), 2) }}
        ৳</th>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
        
                                                        <div class="d-print-none">
                                                            <div class="float-end">
                                                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                            </div>
                                        </div> <!-- end row -->
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                            
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>

            
        </div> 
    </div>
    <!-- End Page-content -->
                
@endsection

@section('page-script')
   
    
    


@endsection