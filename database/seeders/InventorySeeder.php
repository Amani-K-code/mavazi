<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        $file = fopen($filePath, 'r');
        fgetcsv($file); // Skip header row

        $currentItemName = "";

        while(($row = fgetcsv($file)) !== FALSE){
            //1. Detect and build the ItemName
            if(!empty($row[1])){
                $currentItemName = trim($row[2]); //Main Item (e.g., SCHOOL SHIRT)
            } elseif (!empty($row[2])){
                $currentItemName = "".trim($row[2]); //Sub-item (e.g., Yellow pin stripped)
            }

            // 2. Skip rows without price/size data
            if (empty($row[3]) || empty($row[4])) continue;

            \App\Models\Inventory::create([
                'item_name'   => $currentItemName,
                'category'    => $this->getCategory($currentItemName),
                'size_label'  => trim($row[3]),
                'price' => (float)str_replace(',', "", $row[4]),
                'stock_quantity' => 40,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 5,
            ]);
        }

        fclose($file);
        $this->command->info("Inventory seeded succesfully!");
    }

    private function getCategory($name){
        $name = strtolower($name);
        if(str_contains($name, 'shirt') || str_contains($name, 'blouse')) return 'Shirts';
        if(str_contains($name, 'trouser') || str_contains($name, 'skort')) return 'Bottoms';
        if(str_contains($name, 'sweater') || str_contains($name, 'blazer')) return 'Outerwear';
        if(str_contains($name, 'swim')) return 'Sportswear';
        if(str_contains($name, 'socks') || str_contains($name, 'tie')) return 'Accessories';
        return 'General';
    }
}
