<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function loadAllUsers ()
    {
        $all_users = User::all();
        return view('users',compact('all_users'));
    }

    public function loadAddUserForm ()
    {
        return view('add-user');
    }

    public function AddUser (Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|numeric',
            'password' => 'required|confirmed|min:4|max:8',
        ]);
        //register user here


        try{
            $new_user = new User();
            $new_user->full_name = $request->full_name;
            $new_user->email = $request->email;
            $new_user->phone_number = $request->phone_number;
            $new_user->password = Hash::make($request->password);
            $new_user->save();
            return redirect('/users')->with('success','User added successfully');
        }catch(\Exception $e){
            return redirect('/add/user')->with('fail',$e->getMessage());

        }

    }

    public function loadEditForm ($id)
    {
        $user = User::find($id);
        return view('edit-user',compact('user'));
    }

    public function deleteUser ($id)
    {
        try {
            $user = User::where('id',$id);
            $userFullName = $user;
            $user->delete();
            return redirect('/users')->with('success',"User with Id: ". $id." ".'deleted successfully');
        } catch (\Exception $e) {
            return redirect('/users')->with('fail',$e->getMessage());
        }
    }

    public function editUser (Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
        ]);

        try {
        $update_user = User::where('id',$request->user_id)->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);
            return redirect('/add/user')->with('success',"User wir Id: ".$request->user_id.'updated successfully');
        } catch (\Exception $e) {
            return redirect('/add/user')->with('fail',$e->getMessage());
        }
    }
}