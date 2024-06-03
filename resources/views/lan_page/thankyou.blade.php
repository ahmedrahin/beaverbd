<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Order Confirmation || {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }}</title>

    <!--favicon-->
    @php
        $favIcon = \App\Models\Settings::shop_fav();
    @endphp
    @if(!is_null($favIcon))
        <link rel="icon" href="{{ asset($favIcon->fav_icon) }}" type="image/png" />
    @endif

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <style>
        .box {
            background: #035a03cc;
            color: white;
            margin: 25px 0 40px;
            text-align: center;
            padding: 20px;
            border-radius: 7px;
            font-size: 24px;
            line-height: 44px;
        }
        h3 {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
        }
        ul.order-info{
            background: #dbdbdb73;
            padding: 15px;
            border-radius: 6px;
            padding-right: 18px;
            display: flex;
            justify-content: space-around;
        }
        ul.order-info li {
            list-style: none;
            display: inline;
            border-right: 1px dashed #ccc;
            padding: 0 18px;
            font-size: 11px;
            color: black;
            font-weight: 600;
        }
        ul.order-info li label {
            display: block;
            font-weight: normal;
            font-size: 11px;
            color: black;
            margin-bottom: 3px;
        }
        .pay {
            color: black;
            font-size: 11px;
            margin: 27px 0;
            display: block;
        }
        .order-details {
            background: #dbdbdb73;
            padding: 25px;
            border-radius: 6px;
            padding-right: 18px;
            padding-top: 25px;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .order-details h2 {
            font-size: 18px;
            font-weight: 600;
            margin: 5px 0 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            font-size: 12px;
            color: black;
            font-weight: 600;
            padding: 6px 0;
        }
        td {
            font-size: 12px;
            padding: 6px 0;
        }
        tr.product {
            border: 1px dashed #ccc;
            border-right: 0;
            border-left: 0;
        }
        .variation {
            font-size: 8px;
            color: black;
            font-weight: 700;
        }
        .forPhone {
            /* display: none; */
        }
        /* responsive */
        @media(min-width: 320px) and (max-width: 600px){
            .forPhone {
                display: block;
            }
            .box {
                margin: 16px 0 28px;
                text-align: center;
                padding: 14px;
                font-size: 18px;
                line-height: 35px;
            }
            h3 {
                font-size: 14px;
            }
            ul.order-info li {
                padding: 10px 18px;
                display: block;
                border-bottom: 1px dashed #ccc;
                border-right: 0;
            }
            ul.order-info {
                display: inherit;
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- content start -->
    <section class="body-content">
        <div class="container">
            <div class="row">
                <div class=" offset-md-1 col-md-10">
                    <div class="box">
                    অভিনন্দন! আপনার অর্ডারটি আমাদের কাছে সফলভাবে এসে পৌঁছেছে। কিছুক্ষণের মধ্যে আমাদের প্রতিনিধি আপনার সাথে যোগাযোগ করে অর্ডারটি কনফার্ম করবেন।
                    </div>
                    <h3>Thank you. Your order has been received.</h3>
                </div>

                <div class="offset-md-3 col-md-6">
                    <ul class="order-info">
                        <li>
                            <label>Order Number:</label>
                            {{$order->id}}
                        </li>
                        <li>
                            <label>Date:</label>
                            {{ $order->order_date }}
                        </li>
                        <li>
                            <label>Total:</label>
                            {{ $order->total_amn + ( $order->shipping_id == 'free' ? 0 : $order->shipping->charge ) }}৳
                        </li>
                        <li style="border: none;padding-right: 0;">
                            <label>Payment method:</label>
                            ক্যাশ অন ডেলিভারি
                        </li>
                    </ul>

                    <span class="pay">Pay with cash upon delivery.</span>

                    <section class="order-details">
		
		                <h2>Order details</h2>

                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalQuantity = 0;
                                    $totalDiscount = 0;
                                    $totalSubtotal = 0;

                                    // Calculate the total quantity of items in the order and determine discount per item
                                    foreach ($order_item as $item) {
                                        $totalQuantity += $item->quantity;
                                    }
                                    $discountPerItem = 0;
                                    if ($totalQuantity == 3) {
                                        $discountPerItem = 50;
                                    }
                                @endphp

                                @foreach($order_item as $item)
                                    @php
                                        // Calculate product subtotal and discount for each item
                                        $productSubtotal = $item->price * $item->quantity;
                                        $discount = $item->quantity * $discountPerItem;
                                        $totalDiscount += $discount;
                                        $totalSubtotal += $productSubtotal - $discount;
                                    @endphp
                                    <tr class="product">
                                        <td>
                                            {{$item->product->title}}
                                            <div class="forPhone"></div>
                                            <span class="variation">
                                                @if(!is_null($item->size) && !is_null($item->color))
                                                    (size: {{$item->size}} | color: {{$item->color}})
                                                @elseif(!is_null($item->size))
                                                    (size: {{$item->size}})
                                                @elseif(!is_null($item->color))
                                                    (color: {{$item->color}})
                                                @endif
                                            </span>
                                            <strong class="product-quantity">×&nbsp;{{$item->quantity}}</strong>
                                        </td>
                                        <td><span><bdi>{{$productSubtotal - $discount}}.00<span>৳&nbsp;</span></bdi></span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td scope="row">Subtotal:</td>
                                    <td><span>{{$totalSubtotal}}.00<span>৳&nbsp;</span></span></td>
                                </tr>
                                <tr>
                                    <td scope="row">Shipping:</td>
                                    @if( $order->shipping_id == 'free' )
                                    <td><span>00<span>৳&nbsp;</span></span>&nbsp;<small class="shipped_via">Free shipping</small></td>
                                    @else
                                    <td><span>{{$order->shipping->charge}}<span>৳&nbsp;</span></span>&nbsp;<small class="shipped_via">via {{$order->shipping->shipping_name}}</small></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td scope="row">Payment method:</td>
                                    <td>ক্যাশ অন ডেলিভারি</td>
                                </tr>
                                <tr>
                                    <td scope="row">Total:</td>
                                    <td><span>{{ $totalSubtotal + ( $order->shipping_id == 'free' ? 0 : $order->shipping->charge ) }}.00<span>৳&nbsp;</span></span></td>
                                </tr>
                            </tfoot>
                        </table>



                        <!-- <table>
                            @if( !is_null($order->size) )
                                <tbody><tr class="product"><td>আপনার সাইজ সিলেক্ট করুন:</th><td>{{$order->size}}</td></tr></tbody>
                            @elseif( !is_null($order->color) )
                                <tbody><tr><td>আপনার সাইজ সিলেক্ট করুন:</th><td>{{$order->color}}</td></tr></tbody>
                            @endif
                        </table> -->
                    </section>

                </div>
            </div>
        </div>
    </section>
    <!-- content end -->

</body>
</html>