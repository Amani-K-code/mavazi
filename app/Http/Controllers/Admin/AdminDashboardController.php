<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(){
        $stats = [
            'earnings' => [
                'daily' => Sale::whereDate('created_at', today())->where('status', 'CONFIRMED')->sum('total_amount'),
                'weekly' => Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'CONFIRMED')->sum('total_amount'),
                'monthly' => Sale::whereMonth('created_at', now()->month)->where('status', 'CONFIRMED')->sum('total_amount'),
            ],
            
            'stock_health' => [
                'healthy' => Inventory::where('stock_quantity', '>', 10)->count(),
                'low' => Inventory::whereBetween('stock_quantity', [1, 10])-> count(),
                'out' => Inventory::where('stock_quantity','<=', 0)->count(),
            ],

            'booked_value' => Sale::where('status', 'BOOKED')->sum('total_amount'),
            'top_sellers' => $this->getSalesHeatMap()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function getSalesHeatMap(){
        return DB::table('sale_items')
            ->join('inventories', 'sale_items.inventory_id', '=', 'inventories.id')
            ->select('inventories.item_name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('inventories.item_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }
}
