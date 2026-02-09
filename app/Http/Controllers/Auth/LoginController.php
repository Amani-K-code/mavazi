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
            $url = match($role){
                'Admin' => '/admin/dashboard',
                'Storekeeper' => '/storekeeper/dashboard',
                'Cashier' => '/cashier/dashboard',
                default => '/',

            };

            if($request->ajax()){
                return response()->json(['url' => url($url)], 200);
            }
            return redirect()->intended($url);
        }

        //For AJAX failures
        if($request->ajax()){
          return response()->json([
            'errors' => ['user_id_alias' => 'The provided credentials do not match our records.']
          ], 422);
        }

        //Fallback for regular submissions
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
