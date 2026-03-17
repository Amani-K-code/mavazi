<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(){

            // Top 3 Cashiers (Fixing the $q scope error)
            $topCashiers = User::where('role', 'Cashier')
            ->withCount(['sales' => function($query) { 
                // We use $query here. Laravel passes this automatically.
                $query->whereDate('created_at', today()); 
            }])
            ->get()
            ->map(function($user) {
                // Calculate revenue for each of these top cashiers
                $user->total_revenue = $user->sales()
                    ->whereDate('created_at', today())
                    ->sum('total_amount');
                return $user;
            })
            ->sortByDesc('total_revenue')
            ->take(3);


            $ratings = Feedback::with('sale')->latest()->take(2)->get()->map(function($f){
                return (object)[
                    'stars' => $f->rating,
                    'comment' => $f->comment,
                    'customer_name'=> $f->sale->customer_name ??  'Valued Customer',
                ];
            });

            $topItems = SaleItem::select('inventory_id', DB::raw('SUM(quantity) as total_sold'))
                ->with('inventory')
                ->groupBy('inventory_id')
                ->orderByDesc('total_sold')
                ->take(3)
                ->get()
                ->map(function($item) {
                    // We calculate revenue here in PHP using the inventory's price 
                    // to avoid the "Column not found" SQL error
                    $unitPrice = $item->inventory->price ?? 0; 
                    
                    return (object)[
                        'item_name' => $item->inventory->item_name ?? 'Unknown',
                        'category' => $item->inventory->category ?? 'General',
                        'total_sold' => $item->total_sold,
                        'revenue' => $item->total_sold * $unitPrice
                    ];
                });

            $activeBookings = Sale::where ('status', 'BOOKED')->count();
            $lowStockCount = Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count();
                
                

            $dailyTotal = Sale::whereDate('created_at',today())->sum('total_amount');
            $weeklyTotal = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
            $itemsSoldToday = SaleItem::whereHas('sale', function($q){
                $q->whereDate('created_at', today());
            })->sum('quantity');

            $salesData = [];
            $salesLabels = [];
            for ($i = 6; $i >= 0; $i--){
                $date = now()->subDays($i);
                $salesLabels[] = $date->format('D');
                $salesData[] = Sale::whereDate('created_at', $date)->sum('total_amount');
            }


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
            'top_sellers' => $this->getSalesHeatMap(),

            'total_sales' => Sale::where('status', 'CONFIRMED')->sum('total_amount'),
            'top_cashier' => User::where('role', 'Cashier')
                ->withCount('sales')
                ->orderBy('sales_count', 'desc')
                ->first(),

            'low_stock_count' => Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count(),
        ];

        $feedbacks = Feedback::with('sale')->latest()->take(10)->get();

        $hourlySales = Sale::whereDate('created_at', today())
        ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total_amount) as total'))
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

        $recentActivities =  Sale::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 
            'feedbacks', 
            'dailyTotal', 
            'weeklyTotal', 
            'itemsSoldToday', 
            'salesData', 
            'salesLabels', 
            'hourlySales', 
            'recentActivities', 
            'topCashiers', 
            'ratings', 
            'topItems', 
            'activeBookings'));
    }

    public function manageUsers(){
        $users = User::all();
        return view('admin.users', compact('users'));
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

    public function downloadReport(){
        $sales = Sale::with(['user', 'saleItems.inventory'])
                     ->whereMonth('created_at', now()->month)
                     ->get();

        $totalRevenue = $sales->sum('total_amount');

        $categoryStats = \DB::table('sale_items')
            ->join('inventories', 'sale_items.inventory_id', '=', 'inventories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereMonth('sales.created_at', now()->month)
            ->select('inventories.category', DB::raw('SUM(sale_items.subtotal) as total'))
            ->groupBy('inventories.category')
            ->get();

        
            $data = 
            [
                'sales' => $sales,
                'totalRevenue' => $totalRevenue,
                'categoryStats' => $categoryStats,
                'monthName' => now()->format('F'),
                'year' => now()->year,
            ];
            
            $pdf = Pdf::loadView('pdf.report', $data);
            return $pdf->setPaper('a4')->download('Mavazi_Report_'.now()->format('M_Y').'.pdf');
    }


}
