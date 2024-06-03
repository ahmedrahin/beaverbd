<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Mail\OrderEmail;
use App\Models\Settings;
use Mail;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('backend.pages.order.order', compact('orders'));
    }

    // add to cart
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        $cart = session()->get('cart', []);
        $sizes = $request->sizes; 
        $cartKey = $product->id . '-' . $sizes;
    
        // Check if the cart already contains the product with the same size
        if (isset($cart[$cartKey])) {
            // If it does, increment the quantity
            $cart[$cartKey]['quantity']++;
            session()->put('cart', $cart);
            return response()->json(['success' => 'Quantity Updated In Cart']);
        } else {
            // If it doesn't, add the new variation
            $cart[$cartKey] = [
                "id"        => $product->id,
                "name"      => $product->title,
                "image"     => $product->image,
                "quantity"  => 1,
                "price"     => (!is_null($product->offer_price)) ? $product->offer_price : $product->price,
                "sizes"     => $sizes,
            ];
            session()->put('cart', $cart);
            return response()->json(['success' => 'Product added to cart']);
        }
    }

    // update cart
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $quantity = $request->quantity;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true, 'message' => 'Cart updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    // remove from cart
    public function removeFromCart(Request $request)
    {
        $cartKey = $request->id; 
    
        $cart = session()->get('cart', []);
    
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            return response()->json(['success' => 'Item removed from cart']);
        }
    
        return response()->json(['error' => 'Item not found in cart']);
    }
    
    //get cart
    public function getCart()
    {
        $cart = session()->get('cart', []);
        $totalSubtotal = 0;
        $totalQuantity = 0;
        $freeShipping = false;

        // Calculate the total quantity of items in the cart
        foreach ($cart as $id => $item) {
            $totalQuantity += $item['quantity'];
        }

        // Apply the pricing rules based on the total quantity
        if ($totalQuantity == 2) {
            $totalSubtotal = 1300;
        } elseif ($totalQuantity == 3) {
            $totalSubtotal = 1800;
        } elseif ($totalQuantity > 3) {
            foreach ($cart as $id => $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $cart[$id]['subtotal'] = $subtotal;
                $totalSubtotal += $subtotal;
            }
            $freeShipping = true; // Set free shipping for quantities greater than 3
        } else {
            foreach ($cart as $id => $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $cart[$id]['subtotal'] = $subtotal;
                $totalSubtotal += $subtotal;
            }
        }

        return response()->json(['cart' => $cart, 'totalSubtotal' => $totalSubtotal, 'freeShipping' => $freeShipping]);
    }

    //order place
    public function placeOrder(Request $request)
    {
        $request->validate([
            "name"      => "required",
            "address"   => "required",
            "phone"     => "required|numeric",
        ]);

        $cart = session()->get('cart', []);
        $totalQuantity = 0;

        // Calculate the total quantity of items in the cart
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        $totalSubtotal = 0;
        $shippingCharge = 0;

        // Apply pricing rules based on total quantity
        if ($totalQuantity == 2) {
            $totalSubtotal = 1300;
        } elseif ($totalQuantity == 3) {
            $totalSubtotal = 1800;
        } elseif ($totalQuantity > 3) {
            foreach ($cart as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalSubtotal += $subtotal;
            }
            $shippingCharge = 0; // Free shipping for quantities greater than 3
        } else {
            foreach ($cart as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalSubtotal += $subtotal;
            }
            $shippingCharge = $request->shipping_charge; // Include the shipping charge if quantity is less than 4
        }

        $order = Order::create([
            'customer_name'       => $request->name,
            'phone'               => $request->phone,
            'address'             => $request->address,
            'order_date'          => Carbon::now()->format('d/m/Y'),
            'order_status'        => "Pending",
            'shipping_id'         => $request->shippingOption,
            'total_amn'           => $totalSubtotal,
            'month'               => Carbon::now()->format('n'),
            'year'                => Carbon::now()->format('Y'),
            'date'                => Carbon::now()->format('d M, Y'),
        ]);

        // Send mail to admin
        $adminEmail = Settings::shop_email();
        Mail::to($adminEmail)->send(new OrderEmail($order));

        // Create order items and save them to the database
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if (!$product) continue;

            // Create a new order item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = (!is_null($product->offer_price)) ? $product->offer_price : $product->price;
            $orderItem->size = $item['sizes'];

            // Save the order item
            $orderItem->save();
        }

        // Clear the cart after successful checkout
        Session::forget('cart');

        return response()->json([
            'order_id' => $order->id
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function details(string $id)
    {
        $details = Order::find($id);
        $items   = OrderItem::where('order_id', $details->id)->get();
        return view('backend.pages.order.details', compact('details', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = Order::find($id);
        $update->order_status = $request->order_status;
        $update->save();
    }

    // success order
    public function successOrder(string $id){
        $order      = Order::find($id);
        $order_item = OrderItem::where('order_id', $id)->get();
        return view('lan_page.thankyou', compact('order', 'order_item'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Order::find($id);
        if ($delete) {
            $delete->delete();
        }
    }

    // today order
    public function today()
    {   
        $today    = Carbon::now()->format('d/m/Y');
        $orders   = Order::where('order_date', $today)->get();
        return view('backend.pages.order.today', compact('orders'));
    }

    // month expense
    public function month()
    {   
        $month        = Carbon::now()->format('n');
        $monthname    = Carbon::now()->format('M');
        $year         = Carbon::now()->format('Y');
        $date         = null;
        $orders     = Order::where('month', $month)->where('year', $year)->get();
        return view('backend.pages.order.month', compact('orders', 'year', 'monthname', 'date'));
    }

    // monthly expenses
    public function monthlyOrder($month)
    {     
       $monthName = [
           'jan' => 1,
           'feb' => 2,
           'mar' => 3,
           'apr' => 4,
           'may' => 5,
           'jun' => 6,
           'jul' => 7,
           'aug' => 8,
           'sep' => 9,
           'oct' => 10,
           'nov' => 11,
           'dec' => 12,
       ];

       // $theMonth = array_flip($monthName);

       $year = Carbon::now()->format('Y');
       $isMonth = Carbon::now()->format('y');
       $getMonth = ucfirst($month). "-" .$isMonth;
       $theMonth = ucfirst($month);
       $date     = null;
       $orders = Order::where('month', '=', $monthName[$month])->where('year', $year)->get();

       return view('backend.pages.order.month', ['orders' => $orders, 'year' => $year, 'getMonth' => $getMonth, 'theMonth' => $theMonth, 'date' => $date]);
    }

    // monthlyDayExpenses expenses
    public function monthlyDayOrder(Request $request)
    {     
       $date     = $request->input('date');
       $year     = $request->input('year');
       $getMonth = date('d-M', strtotime($date));
       $theMonth = date('M', strtotime($date));
 
       $orders = Order::where('date', $date)->get();
        return view('backend.pages.order.month', ['orders' => $orders, 'year' => $year, 'getMonth' => $getMonth, 'theMonth' => $theMonth, 'date' => $date]);
    }

     // year expense
     public function year()
     {   
         $year     = Carbon::now()->format('Y');
         $orders = Order::where('year', $year)->get();
         return view('backend.pages.order.year', compact('orders', 'year'));
     }

     // pending expense
     public function pending()
     {   
         $orders = Order::where('order_status', "Pending")->get();
         return view('backend.pages.order.pending
         ', compact('orders'));
     }

     //invoice 
     public function order_invoice(string $id){
        $order          = Order::find($id);
        $order_invoice  = Order::where('id', $order->id)->first();
        $items          = OrderItem::where('order_id', $order->id)->get();
        return view('backend.pages.order.invoice', compact('order_invoice', 'items'));
     }
}
