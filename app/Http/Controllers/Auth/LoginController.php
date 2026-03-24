<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'user_id_alias' => 'required|string',
            'password' => 'required',

        ]);

        $credentials = [
            'user_id_alias' => $request->user_id_alias,
            'password' => $request->password,

        ];

        if (Auth::attempt($credentials)){
            $user = Auth::user();

            if(!$user->is_active){
                $name = $user->name;
                Auth::logout();
                

                $message = "Sorry $name, your account is currently deactivated by Admin. Please contact support.";

                if($request->ajax()){
                    return response()->json(['errors' => ['user_id_alias' => [$message]]], 422);
                }
                return back()->withErrors(['user_id_alias' => $message]);
            }
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
        $errorMessage = 'The provided credentials do not match our records.';
        if($request->ajax()){
          return response()->json([
            'errors' => ['user_id_alias' => [$errorMessage]]
          ], 422);
        }

        //Fallback for regular submissions
        return back()->withErrors([
            'user_id_alias' => [$errorMessage],
            ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
