<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Validation\Rule;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // admin profile
    public function profile(){
        return view('backend.pages.profile.profile');
    }

    // admin information
    public function adminInfo(Request $request, string $id)
    {
        $userInfo = User::find($id);

        if( !is_null( $userInfo ) ){
            // validation
            $request->validate([
                'name' => 'required',
                'email' => [
                    'required',
                    Rule::unique('users')->ignore($userInfo->id),
                    function ($attribute, $value, $fail) {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('The '.$attribute.' must be a valid email address.');
                        }
                    },
                ],
            ]);

             // image 
            if($request->hasRemove){
                // delete employee image
                if (File::exists($userInfo->image)) {
                    File::delete($userInfo->image);
                }
                $userInfo->image = null;
            }
            elseif ($request->image) {
                // delete user image
                if (File::exists($userInfo->image)) {
                    File::delete($userInfo->image);
                }

                $manager = new ImageManager(new Driver());
                $name_gan = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();
                $image = $manager->read($request->file('image'));
                $image->save(base_path('public/backend/images/user/' . $name_gan));

                $userInfo->image = 'backend/images/user/' . $name_gan;
            }

            $userInfo->name = $request->name;
            $userInfo->email = $request->email;

            // save
            $userInfo->save();

            return response()->json([
                'html' => view('backend.pages.profile.user-img')->render()
            ]);

        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
