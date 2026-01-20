<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'user_id_alias' => 'required|string',
            'password' => 'required',

        ]);

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            
            $role = Auth::user()->role;
            return match($role){
                'Admin' => redirect()->intended('/admin/dashboard'),
                'Storekeeper' => redirect()->intended('/storekeeper/dashboard'),
                default => redirect()->intended('/cashier/dashboard'),
            };
        }

        return back()->withErrors([
            'user_id_alias' => 'Invalid Credentials']);
    }
}
