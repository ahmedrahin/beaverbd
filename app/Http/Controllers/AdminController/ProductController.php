<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    // manage all product
    public function manage()
    {
       $products = Product::orderBy('id', 'desc')->get();
       return view('backend.pages.product.manage', compact('products'));
    }

    // add product
    public function add()
    {
        return view('backend.pages.product.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'offer_price' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->price) {
                        $fail('The offer price must be less than or equal to the price.');
                    }
                },
            ],
            'code' => 'nullable',
        ]);
        

        $product = new Product();

         // image 
         if ($request->image) {
            $manager = new ImageManager(new Driver());
            $name_gan = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->read($request->file('image'));
            // $image->resize(500, 480);
            $image->save(base_path('public/backend/images/product/' . $name_gan));

            $product->image = 'backend/images/product/' . $name_gan;
        }

        $product->title             = $request->title;
        $product->slug              = Str::slug($request->title);
        $product->sku_code          = $request->code;
        $product->offer_price       = $request->offer_price;
        $product->price             = $request->price;
        $product->status            = 1;
        // save
        $product->save();
    }

    public function activeStatus(Request $request, string $id)
    {   
        // update id
        $update = Product::find($id);
        $update->status = $request->status;
        // save
        $update->save();

        $message = $request->status == 0 ? $update->title . ' is inactive' : $update->title . ' is actived';
        $type    = $request->status == 0 ? 'info' : 'success';
        return response()->json([
            'msg' => $message,
            'type' => $type,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $edit = Product::find($id);
        $editData = Product::where('id', $edit->id)->first();
        return view('backend.pages.product.edit', compact('editData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // update id
        $update = Product::find($id);

        // validation
        $request->validate([
            'title' =>  'required',
            'price' => 'required|numeric',
            'offer_price' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->price) {
                        $fail('The offer price must be less than or equal to the price.');
                    }
                },
            ],
            'code'  => 'nullable',
        ]);

         // image 
        if($request->hasRemove){
            // delete employee image
            if (File::exists($update->image)) {
                File::delete($update->image);
            }
            $update->image = null;
        }
        elseif ($request->image) {
            // delete employee image
            if (File::exists($update->image)) {
                File::delete($update->image);
            }

            $manager = new ImageManager(new Driver());
            $name_gan = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->read($request->file('image'));
            // $image->resize(250, 200);
            $image->save(base_path('public/backend/images/product/' . $name_gan));

            $update->image = 'backend/images/product/' . $name_gan;
        }

        $update->title             = $request->title;
        $update->slug              = Str::slug($request->title);
        $update->sku_code          = $request->code;
        $update->offer_price       = $request->offer_price;
        $update->price             = $request->price;
        $update->status            = $request->status;
        // save
        $update->save();

       // Return success response with the updated employee data
        return response()->json([
            'success' => 'Information updated successfully.',
            'editData' => $update
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Product::find($id);
        if ($delete) {
            $delete->delete();
            // delete product image
            if (File::exists($delete->image)) {
                File::delete($delete->image);
            }
            return response()->json([
                'message' => 'Product deleted successfully.',
            ]);
        } else {
            return response()->json(['error' => 'Product not found.'], 404);
        }
    
    }
}
