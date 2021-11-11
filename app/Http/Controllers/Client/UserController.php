<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('Lunch.User.LunchRegister');
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isAdmin' => 0])) {
            return \redirect(url('tickets'));
        } else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isAdmin' => 1])) {
            return \redirect(route('StatisticByDay',date('Y-m-d')));
        }else{
            return back()->with('fail','Sai email hoặc mật khẩu');
        }
    }
}
