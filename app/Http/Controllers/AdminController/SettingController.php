<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage()
    {   
        $setting = Settings::first();
        return view('backend.pages.settings.manage', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $setting = Settings::find(1);

        $request->validate([
            'email' => 'required'
        ]);

        // logo 
        if ($request->logo) {
            // logo
            if (File::exists($setting->logo)) {
                File::delete($setting->logo);
            }

            $manager = new ImageManager(new Driver());
            $name_gan = hexdec(uniqid()) . '.' . $request->file('logo')->getClientOriginalExtension();
            $image = $manager->read($request->file('logo'));
            $image->save(base_path('public/backend/images/' . $name_gan));

            $setting->logo = 'backend/images/' . $name_gan;
        }

        // fav icon
        if ($request->fav) {
            // logo
            if (File::exists($setting->fav)) {
                File::delete($setting->fav);
            }

            $manager = new ImageManager(new Driver());
            $name_gan = hexdec(uniqid()) . '.' . $request->file('fav')->getClientOriginalExtension();
            $image = $manager->read($request->file('fav'));
            $image->save(base_path('public/backend/images/' . $name_gan));

            $setting->fav_icon = 'backend/images/' . $name_gan;
        }

        $setting->company_name  = $request->name;
        $setting->email         = $request->email;
        $setting->phone         = $request->phone;
        $setting->address       = $request->address;
        $setting->city          = $request->city;
        $setting->zip           = $request->zip;
        $setting->save();

    }

}
