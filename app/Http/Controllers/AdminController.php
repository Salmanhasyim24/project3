<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }


    public function adminlogout(Request $request)
    {
          Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    } // End Mehtod

    public function AdminProfile(User $user)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('admin.profile', [
            'user' => $user
        ]);
    }

    public function AdminProfileStore(Request $request, User $user)
    {
      $id = Auth::user()->id;
        $user = User::find($id);
    //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username'     => 'nullable|min:5',
            'phone'   => 'nullable|min:5',
            'address'     => 'nullable|min:5'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('upload/admin_images', $image->hashName());

            //delete old image
            Storage::delete('upload/admin_images/'.$user->image);

            //update user with new image
            $user->update([
                'image'     => $image->hashName(),
                'username'     => $request->username,
                'phone'   => $request->phone,
                'address'   => $request->address,
                'phone'   => $request->phone,

            ]);

        } else {

            //update post without image
            $user->update([
                'username'     => $request->username,
                'phone'   => $request->phone,
                'address'   => $request->address,
                'phone'   => $request->phone,
            ]);
        }
        $user->save();
           $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return to_route('admin.profile')->with($notification);

    } // End Mehtod

    public function AdminChangePassword()
    {
        return view('admin.changepassword');
    }
      public function AdminUpdatePassword(Request $request){
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        // Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");

    } // End Mehtod

 }
