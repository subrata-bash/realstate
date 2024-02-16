<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');
    }

    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->regenerateToken();
        return redirect('admin/login');

    }

    public function adminLogin()
    {
        return view('admin.admin_login');
    }

    public function adminProfile()
    {
        $id = Auth::user()->id; // find the user id who is logged in
        $profileData = User::find($id); // use eloquent model User to fetch all the data

        return view('admin.admin_profile_view', [
            'profileData' => $profileData,
        ]);
    }

    public function adminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        // Here we update the data
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        // Pic upload here
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            // delete the old photo when new uploaded
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();
        $notification = [
          'message' => 'Admin Profile Updated Successfully',
          'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);

    }

    public function adminChangePassword()
    {
        $id = Auth::user()->id; // find the user id who is logged in
        $profileData = User::find($id); // use eloquent model User to fetch all the data
        return view('admin.admin_change_password', [
            'profileData' => $profileData,
        ]);
    }

    public function adminUpdatePassword(Request $request)
    {
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required |confirmed'
        ]);

        // Match the Hashed password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = [
              'message' => 'old Password is Not Match!',
              'alert_type' => 'error',
            ];
            return back()->with($notification);
        }

        // Update new password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $notification = [
            'message' => 'Password is Change Successfully',
            'alert_type' => 'success',
        ];
        return back()->with($notification);

    }

}
