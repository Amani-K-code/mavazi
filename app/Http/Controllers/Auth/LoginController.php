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
                'Cashier' => redirect()->intended('/cashier/dashboard'),
                default => redirect('/'),

            };
        }

        return back()->withErrors([
            'user_id_alias' => 'The provided credentials do not match our records.',
            ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
