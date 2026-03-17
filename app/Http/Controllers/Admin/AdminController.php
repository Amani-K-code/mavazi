<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function toggleUserStatus(User $user){

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User account has been {$status} successfully.");
    }



    public function downloadCredentials($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() != $id && auth()->user()->role !== 'Admin') {
            abort(403, 'Unauthorized access to credentials.');
        }
        
        
        session()->keep(['raw_password','registered_user_id', 'new_alias', ]);

        $data = [
            'name' => $user-> name,
            'email' => $user->email,
            'alias' => $user->user_id_alias ,
            'raw_password' => session('raw_password'),
            'role' => $user->role,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdf.cashier_credentials', $data);
        return $pdf->download("Credentials_{$user->name}.pdf");

    }
}
