<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function profile()
    {
        $id = Auth::id();
        $adminUser = User::with('provider.zipCodes')->find($id);

        return view('admin.profile.index', compact('adminUser'));
    }
    public function editProfile() {
        $id = Auth::user()->id;
        $editData = User::find($id);

        return view('admin.profile.edit', compact('editData'));
    }

    public function storeProfile(Request $request) {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name     = $request->name;
        $data->email    = $request->email;

        if($request->file('image')) {
            $file        = $request->file('image');
            $fileName    = date('YmdHi') . $file->getClientOriginalName();

            $file->move(public_path('upload/admin_images'), $fileName);
            $data['image'] = $fileName;
        }
        $data->save();
        $notification = array(
            'message' => 'Admin profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     * view change password page
     */
    public function changePassword() {

        return view('admin.change_password.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     * change password
     */
    public function updatePassword(Request $request) {
        $validateData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        $hashedPassword = Auth::user()->password;

        if(Hash::check($request->old_password, $hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = bcrypt($request->new_password);

            $user->save();

            session()->flash('message', 'Password updated successfully!');

        } else {

            session()->flash('message', 'Old password is not matched!');

        }
        return redirect()->back();
    }

}
