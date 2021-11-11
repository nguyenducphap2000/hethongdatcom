<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
        return view('Lunch.profile');
    }
    public function update(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        $password = "";
        if(!empty($request->password)){
            $request->validate([
                'password' => 'min:8',
                'RePassword' => 'min:8|same:password'
            ]);
            $password = Hash::make($request->password);
        }else{
            $password = Auth::user()->password;
        }
        $data = User::where('id',Auth::user()->id)->update([
            'name' => $request->name,
            'password' => $password
        ]);
        if($data){
            return back()->with('profileSuccess','Successfully');
        }else{
            return back()->with('profileFail','Failed');
        }
    }
}
