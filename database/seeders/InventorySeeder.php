<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/logos_school_uniform_pricelist.csv');
        if(!file_exists($filePath)) {
            $this->command->error("CSV file not found at $filePath");
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Inventory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $file = fopen($filePath, 'r');
        fgetcsv($file); // Skip header row

        $currentItemName = "";

        while(($row = fgetcsv($file)) !== FALSE){
            //1. Detect and build the ItemName
            if(!empty($row[2])){
                $currentItemName = trim($row[2]); //Main Item (e.g., SCHOOL SHIRT)
            }

            // 2. Skip rows without price/size data
            if (empty($row[3]) || empty($row[4])) continue;

            Inventory::create([
                'item_name'   => $currentItemName,
                'category'    => $this->getCategory($currentItemName),
                'size_label'  => trim(str_replace('Size:', '', $row[3])),
                'price' => (float)preg_replace('/[^0-9.]/', "", $row[4]),
                'stock_quantity' => 40,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 5,
            ]);
        }

        fclose($file);
        $this->command->info("Inventory seeded succesfully! 🌱");
    }

    private function getCategory($name){
        $name = strtolower($name);
        if(str_contains($name, 'junior school')) return 'Junior School';
        if(str_contains($name, 'shirt') || str_contains($name, 'blouse')) return 'Shirts';
        if(str_contains($name, 'trouser') || str_contains($name, 'skort')|| str_contains($name, 'skirt')) return 'Bottoms';
        if(str_contains($name, 'sweater') || str_contains($name, 'blazer')|| str_contains($name, 'fleece')||str_contains($name, 'jacket')) return 'Outerwear';
        if(str_contains($name, 'swim')|| str_contains($name, 'track suit')) return 'Sportswear';
        if(str_contains($name, 'socks') || str_contains($name, 'tie') || str_contains($name, 'stocking')) return 'Accessories';
        return 'General';
    }
}
