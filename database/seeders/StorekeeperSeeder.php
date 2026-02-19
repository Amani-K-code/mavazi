<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorekeeperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::where('user_id_alias', '000')->update(['name' => 'Superadmin']);
        User::where('user_id_alias', '001')->update(['name' => 'Principal']);
        User::where('user_id_alias', '002')->update(['name' => 'Head of Finance']);

        User::where('user_id_alias', '006')->update(['name' => 'Animet']);
        User::where('user_id_alias', '007')->update(['name' => 'LCS Uniforms']);
        User::where('user_id_alias', '008')->update(['name' => 'School Outfitters']);



        // 3. Flag Important Items using'is_flagged' column
        $importantItem = [
            'POLO T-SHIRT', 'SCHOOL SHIRT - Yellow pin stripped', 'PETER PAN BLOUSES',
        'TROUSERS (Half Elastic)', 'SKORT SKIRTS', 'SWEATERS', 'FLEECE JACKETS',
        'TRACK SUITS', 'SOCKS (MID-LENGTH)', 'SCHOOL TIE - PRIMARY',
        'SHIRTS - Sky Blue', 'BLAZER', 'CHECKED SKIRTS', 'TROUSERS', 'JUNIOR HIGH SCHOOL TIE'
        ];

        foreach ($importantItem as $name) {
            Inventory::where('item_name', 'like', "%$name%")->update(['is_flagged' => true]);
        }
    }
}
