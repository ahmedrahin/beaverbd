<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage()
    {   
        $shippings = Shipping::all();
        return view('backend.pages.shipping.shipping', compact('shippings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'charge'    => 'required|numeric'
        ]);

        $shipping = new Shipping();
        $shipping->shipping_name = $request->name;
        $shipping->charge        = $request->charge;
        $shipping->status        = 1;
        $shipping->save();

        $shippings = Shipping::all();
        return response()->json([
            "html" => view('backend.pages.shipping.details', compact('shippings'))->render()
        ]);
    }

    public function activeStatus(Request $request, string $id)
    {   
        // update id
        $update = Shipping::find($id);
        $update->status = $request->status;
        // save
        $update->save();

        $message = $request->status == 0 ? 'Shipping status is off' : 'Shipping status is on';
        $type    = $request->status == 0 ? 'info' : 'success';
        return response()->json([
            'msg' => $message,
            'type' => $type,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Shipping::find($id);
        if ($delete) {
            $delete->delete();

            $shippings = Shipping::all();
            return response()->json([
                "html" => view('backend.pages.shipping.details', compact('shippings'))->render()
            ]);
        }
    }
}
