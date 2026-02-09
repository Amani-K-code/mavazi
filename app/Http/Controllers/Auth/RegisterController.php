<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            ]);

            //Restricts registration to @logos.ac.ke and Role: Cashier only for now
            if (!str_ends_with($request->email,'@logos.ac.ke')){
                return back()->withErrors(['email'=> 'Only Logos Christian School emails are permitted.']);
            }

            $lastAlias = User::orderBy('user_id_alias', 'desc')->first()->user_id_alias;
            $newAlias = str_pad((int)$lastAlias + 1, 3, '0', STR_PAD_LEFT);

            $user = User::create([
                'name'=>$request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'user_id_alias'=>$newAlias,
                'role'=>'Cashier',
            ]);

            //Flash Data to session to be shown on success page
            return redirect()->route('register.success')->with([
                'new_alias'=>$newAlias,
                'new_name'=> $request->name,
                'raw_password'=> $request->password
            ]);
    }
}
