<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ !is_null($siteTitle = App\Models\Settings::site_title()) ? $siteTitle->company_name : 'Shop' }}</title>

    <!--favicon-->
    @php
        $favIcon = \App\Models\Settings::shop_fav();
    @endphp
    @if(!is_null($favIcon))
        <link rel="icon" href="{{ asset($favIcon->fav_icon) }}" type="image/png" />
    @endif

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- toastr -->
    <link rel="stylesheet" href="{{asset('toastr.min.css')}}">

    <link rel="stylesheet" href="{{ asset('custom.css') }}">
</head>
<body>

    {{-- card button --}}
    <!-- <a href="#chckout" class="cardQty">
        <div class="qtyCount">0</div>
        <i class="fa fa-cart-plus" aria-hidden="true"></i>
    </a> -->

    <!-- content start -->
    <section class="body-content">
        <div class="container">
            <div class="row">
                <h1>ইতিহাসের সবচেয়ে সেরা হাফ-শার্টের অফার🔥🔥🔥</h1>
                <div class=" offset-md-1 col-md-10">
                    <h4 class="sub-title">১০০% এক্সপোর্ট কোয়ালিটি গ্যারান্টি</h4>
                    <div class="video">
                    <iframe width="1263" height="480" src="https://www.youtube.com/embed/otvND6bu3yA" title="Summer 𝗛𝗮𝗹𝗳 𝗦𝗹𝗲𝗲𝘃𝗲 𝗦𝗵𝗶𝗿𝘁 Collection || New Arrival🔰 Unique &amp; Style Shirt in Banglades" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>

                    <button class="btn btn-primary" id="orderBtnClick">
                        <a href="#products">
                            আপনার পচ্ছন্দের প্রোডাক্টটি অর্ডার করুন
                            <i class="fa fa-hand-o-down" aria-hidden="true"></i>
                        </a>
                    </button>

                    <div class="chart">
                        <img src="{{asset('backend/images/Half-shirt.jpg')}}" alt="">
                    </div>

                    {{-- all product show --}}
                    <div class="row products g-lg-4 g-md-4 g-2" id="products">
                        <h2>আপনি আপনার ইচ্ছে মতন যেকোনো ব্রান্ডের , যেকোনো সাইজের শার্ট নিতে পারেন</h2>
                        @foreach($products as $product)
                            <div class="col-md-3 col-lg-3 col-6">
                                <div class="product-box">
                                    <div class="items-info">
                                        <div class="img-box">
                                            @if(!is_null($product->offer_price))
                                                @php
                                                    $regularPrice = $product->price;
                                                    $offerPrice = $product->offer_price;
                                                    $discountPercentage = (($regularPrice - $offerPrice) / $regularPrice) * 100;
                                                @endphp
                                                <div class="discount">
                                                    -{{ round($discountPercentage) }}%
                                                </div>
                                            @endif
                                            @if(!is_null($product->image))
                                                <img src="{{ asset($product->image) }}" alt="">
                                            @else
                                                <img src="{{ asset('backend/images/default.jpg') }}" alt="">
                                            @endif
                                        </div>
                                        <ul class="product-info">
                                            <li>
                                                <span class="title">{{ $product->title }}</span>
                                            </li>
                                            <li>
                                                @if(!is_null($product->offer_price))
                                                    <span class="price">{{ $product->offer_price }}৳</span>
                                                    <del class="text-danger">{{ $product->price }}৳</del>
                                                @else
                                                    <span class="price">{{ $product->price }}৳</span>
                                                @endif
                                            </li>
                                        </ul>
                                        <div class="variation-item">
                                            <div class="variation-table">
                                                <div class="variation-row">
                                                    <div class="variation-label"><label>Size:</label></div>
                                                    <div class="variation-values">
                                                        <label>
                                                            <input type="checkbox" name="size-{{ $product->id }}" value="S" class="size"> S
                                                        </label>
                                                        <label>
                                                            <input type="checkbox" name="size-{{ $product->id }}" value="M" class="size"> M
                                                        </label>
                                                        <label>
                                                            <input type="checkbox" name="size-{{ $product->id }}" value="L" class="size"> L
                                                        </label>
                                                        <label>
                                                            <input type="checkbox" name="size-{{ $product->id }}" value="XL" class="size"> XL
                                                        </label>
                                                        <label>
                                                            <input type="checkbox" name="size-{{ $product->id }}" value="XXL" class="size"> XXL
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="add-to-cart" data-id="{{ $product->id }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add to Cart</button>
                                </div>
                            </div>
                        @endforeach

                    </div>
                   
                    <div class="chckout" id="chckout">
                        <h3>অর্ডার কন্ফার্ম করতে সঠিক তথ্য দিয়ে নিচের ফর্ম পূরন করুন​</h3>
                        <div class="errors">
                            <ul>
                                
                            </ul>
                        </div>
                        <form action="{{route('place.order')}}" method="post" class="needs-validation"  novalidate>
                            @csrf
                            <div class="row g-md-5">
                                <div class="col-md-6">
                                    <h5>Billing details</h5>
                                    <div class="mb-3">
                                        <label for="name">আপনার নাম লিখুন <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" placeholder="আপনার নাম লিখুন" name="name" id="name" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address">আপনার ঠিকানা লিখুন <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" placeholder="আপনার ঠিকানা লিখুন" name="address" id="address" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">আপনার মোবাইল নাম্বারটি লিখুন <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" placeholder="আপনার মোবাইল নাম্বারটি লিখুন" name="phone" id="phone" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="size">আপনার সাইজ সিলেক্ট করুন <span class="text-danger">*</span> </label>
                                        <div class="variation">
                                            <input type="radio" name="size" id="size" value="M">
                                            <label for="size">M</label>
                                            <input type="radio" name="size" id="L" value="L">
                                            <label for="L">L</label>
                                            <input type="radio" name="size" id="XL" value="XL">
                                            <label for="XL">XL</label>
                                            <input type="radio" name="size" id="XLL" value="XLL">
                                            <label for="XLL">XLL</label>
                                        </div>
                                    </div> -->
                                    
                                </div>

                                <div class="col-md-6">
                                    <h5>Your order</h5>

                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product">Product</th>
                                                <th class="qty">Qty</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody class="items">
                                            @if(count($cart) > 0)
                                                @foreach($cart as $id => $item)
                                                    <tr class="cart-item" data-id="{{ $id }}">
                                                        <td>
                                                            @if(!is_null($item['image']))
                                                                <img src="{{ asset($item['image']) }}" alt="">
                                                            @else
                                                                <img src="{{ asset('backend/images/default.jpg') }}" alt="">
                                                            @endif
                                                            {{ $item['name'] }}
                                                        </td>
                                                        <td class="qty">
                                                            <div class="qty-box">
                                                                <div class="input-group">
                                                                    <span class="input-group-prepend">
                                                                        <button type="button" class="btn quantity-left-minus" data-type="minus" data-id="{{ $id }}">
                                                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </span>
                                                                    <input type="text" name="qty" class="input-number" value="{{ $item['quantity'] }}" min="1" data-id="{{ $id }}">
                                                                    <span class="input-group-prepend">
                                                                        <button type="button" class="btn quantity-right-plus" data-type="plus" data-id="{{ $id }}">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $item['price'] }}৳
                                                        </td>
                                                        <td>
                                                            <button type="button" class="delCart" data-id="{{ $id }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-danger">Your cart is empty</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        <tfoot>
                                            <tr class="subtotal">
                                                <td colspan="2">Subtotal</td>
                                                <td class="sub">৳</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Shipping</td>
                                                <td colspan="2">
                                                <div class="shipping-options">
                                                    @foreach($shippings as $index => $shipping)
                                                    <div class="shipping-info">
                                                        <input type="radio" name="shippingOption" id="shipping{{ $index }}" value="{{ $shipping->id }}" class="charge" {{ $index === 0 ? 'checked' : '' }} data-charge="{{ $shipping->charge }}">
                                                        <label for="shipping{{ $index }}">{{ $shipping->shipping_name }}: {{ $shipping->charge }}৳</label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                </td>
                                            </tr>
                                            <tr id="total">
                                                <td colspan="2">Total</td>
                                                <td id="subTotal">৳</td>
                                            </tr>
                                        </tfoot>



                                    </table>

                                    <div class="deliver-box">
                                        <h5>ক্যাশ অন ডেলিভারি</h5>
                                        <span>১০০% নিশ্চিত হয়ে অর্ডার করুন। আপনার একটি ফেইক অর্ডার হালাল ব্যবসায় ক্ষতি করে</span>
                                    </div>

                                    <button class="btn btn-primary" id="placeOrder" type="submit">
                                        Place Order
                                    </button>
                                    <div class="empty">
                                        <span class="text-danger">Your Cart is Empty</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <footer>
            <ul>
                <li>
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <a href="#">Privacy & Policy</a>
                </li>
                <li>
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <a href="#">Terms & Conditions</a>
                </li>
                <li>
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <a href="#">Return & Refund Policy</a>
                </li>
            </ul>
            <p>Copyright © 2024. All Rights Reserved To PFrontend IT.</p>
        </footer>
    </section>
    <!-- content end -->
    

    <script src="{{ asset('backend/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
     <script src="{{asset('toastr.min.js')}}"></script>
     <script src="{{asset('backend/js/pages/form-validation.init.js')}}"></script>
     
     <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

         $(document).ready(function() { 
            // Add to cart
    $('.add-to-cart').click(function(e) {
        e.preventDefault();

        var productId = $(this).data('id');
        var sizes = [];

        // Collect selected sizes
        $('input[name="size-' + productId + '"]:checked').each(function() {
            sizes.push($(this).val());
        });

        if (sizes.length === 0) {
            toastr.error('Please select at least one size.');
            return;
        }

        $.ajax({
            url: '{{ route('cart.add') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                sizes: sizes.join('-')
            },
            success: function(response) {
                toastr.success(response.success);
                updateCart();

                $('html, body').animate({
                    scrollTop: $('#chckout').offset().top
                }, 500);
            }
        });
    });

    function updateTotal(subtotal, totalQuantity) {
        var shippingCharge = 0;

        if (totalQuantity > 3) {
            // Only show Free Shipping when total quantity is more than 3
            $('.shipping-options').html(
                '<div class="shipping-info">' +
                '<input type="radio" name="shippingOption" id="freeShipping" value="free" class="charge" data-charge="0" checked>' +
                '<label for="freeShipping">Free Shipping: 0৳</label>' +
                '</div>'
            );
        } else {
            // Restore the original shipping options when total quantity is 3 or less
            var shippingOptionsHtml = '';
            @foreach($shippings as $index => $shipping)
                shippingOptionsHtml += '<div class="shipping-info">' +
                    '<input type="radio" name="shippingOption" id="shipping{{ $index }}" value="{{ $shipping->id }}" class="charge" data-charge="{{ $shipping->charge }}" {{ $index === 0 ? 'checked' : '' }}>' +
                    '<label for="shipping{{ $index }}">{{ $shipping->shipping_name }}: {{ $shipping->charge }}৳</label>' +
                    '</div>';
            @endforeach
            $('.shipping-options').html(shippingOptionsHtml);
        }

        // Calculate the total amount
        shippingCharge = parseFloat($('input[name="shippingOption"]:checked').data('charge')) || 0;
        $('#subTotal').text((subtotal + shippingCharge) + '৳');
    }

    // Function to update the cart and call updateTotal
    function updateCart() {
        $.ajax({
            url: '{{ route('cart.get') }}',
            method: "GET",
            success: function(response) {
                var cart = response.cart;
                var totalSubtotal = response.totalSubtotal;
                var totalQuantity = 0;

                $('.items').empty();

                if (Object.keys(cart).length > 0) {
                    $.each(cart, function(id, item) {
                        totalQuantity += item.quantity;

                        $('.items').append(
                            '<tr class="cart-item" data-id="' + id + '">' +
                            '<td>' +
                            (item.image ? '<img src="{{ asset('') }}' + item.image + '" alt="">' : '<img src="{{ asset('backend/images/default.jpg') }}" alt="">') +
                            ' ' + item.name +
                            '</td>' +
                            '<td class="qty">' +
                            '<div class="qty-box">' +
                            '<div class="input-group">' +
                            '<span class="input-group-prepend">' +
                            '<button type="button" class="btn quantity-left-minus" data-type="minus" data-id="' + id + '">' +
                            '<i class="fa fa-minus" aria-hidden="true"></i>' +
                            '</button>' +
                            '</span>' +
                            '<input type="text" name="qty" class="input-number" value="' + item.quantity + '" min="1" data-id="' + id + '">' +
                            '<span class="input-group-append">' +
                            '<button type="button" class="btn quantity-right-plus" data-type="plus" data-id="' + id + '">' +
                            '<i class="fa fa-plus" aria-hidden="true"></i>' +
                            '</button>' +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '<td>' + item.price + '৳</td>' +
                            '<td><button type="button" class="delCart" data-id="' + id + '"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                            '</tr>'
                        );
                    });

                    // Show the cart table and update the subtotal
                    $('table').css('display', 'block');
                    $('.deliver-box').css('display', 'block');
                    $('#placeOrder').css('display', 'block');
                    $('.subtotal .sub').text(totalSubtotal + '৳');
                    updateTotal(totalSubtotal, totalQuantity);
                    $('.empty').css('display', 'none');
                } else {
                    // Hide the cart table when empty
                    $('table').css('display', 'none');
                    $('.deliver-box').css('display', 'none');
                    $('#placeOrder').css('display', 'none');
                    $('.subtotal .sub').text('0৳');
                    $('.empty').css('display', 'flex');
                    updateTotal(0, 0);
                }
            }
        });
    }

    // Ensure the shipping options update on quantity change
    $(document).on('input', '.input-number', function() {
        var $input = $(this);
        var currentVal = parseInt($input.val()) || 0;
        var productId = $input.data('id');

        if (currentVal < 1) {
            $input.val(1);
            toastr.error('Value cannot be less than 1. Value set to 1');
            currentVal = 1;
        }

        // Update the quantity in the session
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: productId,
                quantity: currentVal
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    updateCart(); // Refresh the cart
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while updating the cart.');
            }
        });
    });

    // Ensure the shipping options update on quantity button click
    $(document).on('click', '.quantity-right-plus, .quantity-left-minus', function() {
        var $button = $(this);
        var $input = $button.closest('.qty-box').find('.input-number');
        var currentVal = parseInt($input.val()) || 0;
        var newQty;
        var productId = $button.data('id');

        if ($button.data('type') === 'plus') {
            newQty = currentVal + 1;
        } else if ($button.data('type') === 'minus' && currentVal > 1) {
            newQty = currentVal - 1;
        } else {
            return;
        }

        $input.val(newQty);

        // Update the quantity in the session
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: productId,
                quantity: newQty
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    updateCart(); // Refresh the cart
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while updating the cart.');
            }
        });
    });

    // Update the total when the shipping option is changed
    $(document).on('change', 'input[name="shippingOption"]', function() {
        var subtotalText = $('.subtotal .sub').text();
        var subtotal = parseFloat(subtotalText.replace(/[^\d.-]/g, '')) || 0;
        var totalQuantity = 0;

        // Calculate the total quantity across all cart items
        $('.input-number').each(function() {
            totalQuantity += parseInt($(this).val()) || 0;
        });

        updateTotal(subtotal, totalQuantity);
    });


    // Initial call to update the cart on page load
    updateCart();





            // Remove from cart
            $(document).on('click', '.delCart', function() {
                var productId = $(this).data('id');

                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: productId 
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.warning(response.success);
                            updateCart();
                            
                        } else {
                            toastr.error(response.error);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while removing the product from the cart.');
                    }
                });
            });

            // place order
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
                        $("#placeOrder").prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        `);
                    },
                    success: function(response) {
                        $("#placeOrder").prop('disabled', false).html(`
                            Place Order
                        `);

                        toastr.success('Order Placed Successfully');

                        // redirect success page
                        setTimeout(() => {
                            let orderId = response.order_id;
                            let url = "{{ route('success.order', ['order_id' => ':order_id']) }}";
                            url = url.replace(':order_id', orderId);

                            // Redirect to the constructed URL
                            window.location.href = url;
                        }, 1000);
                       
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Reset Bootstrap validation state
                        form.find('.form-control').removeClass('is-invalid');
                        form.find('.invalid-feedback').html('');
                        $("#placeOrder").prop('disabled', false).html('Place Order');
                        toastr.error('Something is wrong! Please try again');
                        $('.errors').css('display', 'block');
                        $('html, body').animate({
                            scrollTop: $('#chckout').offset().top
                        }, 500);
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        $('.errors ul').empty(); // Clear previous error messages
                        $.each(errors, function(key, value) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').html(value);
                            $('.errors ul').append(`
                                <li>
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    ${value}
                                </li>
                            `);
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
</body>
</html>