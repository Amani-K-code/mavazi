<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff=[
            //Admins(Reserved 000 - 005)
            ['alias'=> '000', 'email'=>'superadmin@logos.ac.ke', 'role' =>'Admin', 'pass' => '1324@adm1n'],
            ['alias'=> '001', 'email'=>'principal@logos.ac.ke', 'role' =>'Admin', 'pass' => '5768@adm1n'],
            ['alias'=> '002', 'email'=>'finance@logos.ac.ke', 'role' =>'Admin', 'pass' => '9012@adm1n'],
            //Storekeepers (006 - 010)
            ['alias'=> '006', 'email'=>'store1@logos.ac.ke', 'role' =>'Storekeeper', 'pass' => '1950@st0re'],
            ['alias'=> '007', 'email'=>'store2@logos.ac.ke', 'role' =>'Storekeeper', 'pass' => '1960@st0re'],
            ['alias'=> '008', 'email'=>'store3@logos.ac.ke', 'role' =>'Storekeeper', 'pass' => '1970@st0re'],

            //First Cashier (Starting at 011)
            ['alias'=> '011', 'email'=>'cashier1@logos.ac.ke', 'role' =>'Cashier', 'pass' => '0000@cash1er'],
        ];

        foreach ($staff as $user){
            \App\Models\User::updateOrCreate(
                ['user_id_alias' => $user['alias']],
                [
                    'name' => ucfirst($user['role']). 'User'. $user['alias'],
                    'email' => $user['email'],
                    'password' =>  Hash::make($user['pass']),
                    'role' => $user['role'],
                ]
                );
        }
    }
}
