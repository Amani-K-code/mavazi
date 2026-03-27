<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use App\Models\Feedback;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class AdminDashboardController extends Controller
{
    public function index(){

            $historicalSales = Sale::select(Db::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                               ->groupBy('date')->orderBy('date', 'desc')->get();

            $ratingDist = Feedback::select('rating', DB::raw('count(*) as count'))
                                ->groupBy('rating')
                                ->pluck('count', 'rating')
                                ->toArray();
            $ratingData = [];
            for ($i = 1; $i <= 5; $i++) { $ratingData[] = $ratingDist[$i] ?? 0; }

            $categoryDist = Inventory::select('category', DB::raw('SUM(stock_quantity) as total'))
                                ->groupBy('category')
                                ->get();

            $orderStatus = [
                'Pending' => Deliveries::where('status', 'PENDING')->count(),
                'Confirmed' => Deliveries::where('status', 'CONFIRMED')->count(),
                'Booked' => Sale::where('status', 'BOOKED')->count(),
            ];

            $lowStockItems = Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                ->orderBy('stock_quantity', 'asc')
                                ->take(5)
                                ->get();

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
                    'cashier_name' => $f->sale->user->name ?? 'Unknown Cashier',
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
                
                

            $dailyTotal = Sale::whereDate('created_at',Carbon::now())->sum('total_amount');
            $weeklyTotal = Sale::whereBetween('created_at', [\Illuminate\Support\Carbon::now()->startOfWeek(), \Illuminate\Support\Carbon::now()->endOfWeek()])->sum('total_amount');
            $itemsSoldToday = SaleItem::whereHas('sale', function($q){
                $q->whereDate('created_at', Carbon::now());
            })->sum('quantity');

            $salesData = [];
            $salesLabels = [];
            for ($i = 6; $i >= 0; $i--){
                $date = Carbon::now()->subDays($i);
                $salesLabels[] = $date->format('D');
                $salesData[] = Sale::whereDate('created_at', $date)->sum('total_amount');
            }


        $stats = [
            'earnings' => [
                'daily' => Sale::whereDate('created_at', Carbon::now())->where('status', 'CONFIRMED')->sum('total_amount'),
                'weekly' => Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status', 'CONFIRMED')->sum('total_amount'),
                'monthly' => Sale::whereMonth('created_at', Carbon::now()->month)->where('status', 'CONFIRMED')->sum('total_amount'),
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
            'historicalSales',
            'ratingData',
            'categoryDist',
            'orderStatus',
            'lowStockItems',
            'stats', 
            'feedbacks', 
            'dailyTotal', 
            'weeklyTotal', 
            'itemsSoldToday', 
            'salesData', 
            'salesLabels', 
            'hourlySales', 
            'recentActivities',
            'lowStockCount', 
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



    public function downloadReport(Request $request) {
        ini_set('memory_limit', '512M');
        set_time_limit(300);
        
        $query = \App\Models\Sale::with(['user', 'saleItems.inventory']); 

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }
        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $sales = $query->latest()->get();

        // Calculations
        $totalRevenue = $sales->sum('total_amount');
        $totalSalesCount = $sales->count();
        $avgOrder = $totalSalesCount > 0 ? ($totalRevenue / $totalSalesCount) : 0;

        // Dates (Using both naming conventions to stop the "Undefined" errors)
        $generatedAt = now()->format('d M Y, h:i A');
        $generated_at = $generatedAt; 
        
        $monthName = $request->filled('month') 
            ? \Carbon\Carbon::create()->month($request->month)->format('F') 
            : now()->format('F');
        $year = now()->format('Y');

        // Category Stats
        $categoryStats = \Illuminate\Support\Facades\DB::table('sale_items')
            ->join('inventories', 'sale_items.inventory_id', '=', 'inventories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereMonth('sales.created_at', $request->month ?? now()->format('m'))
            ->select('inventories.category', \Illuminate\Support\Facades\DB::raw('SUM(sale_items.subtotal) as total'))
            ->groupBy('inventories.category')
            ->get();

        // Summary Array
        $summary = [
            'total_revenue' => $totalRevenue,
            'total_sales'   => $totalSalesCount,
            'total_orders'  => $totalSalesCount,
            'avg_order'     => $avgOrder,
            'generated_at'  => $generatedAt,
            'period'        => $monthName . ' ' . $year,
        ];

        $data = [
            'sales'         => $sales,
            'totalRevenue'  => $totalRevenue,
            'avgOrder'      => $avgOrder,
            'categoryStats' => $categoryStats,
            'monthName'     => $monthName,
            'year'          => $year,
            'summary'       => $summary,
            'generated_at'  => $generatedAt,
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report', compact(
            'sales',
            'totalRevenue',
            'totalSalesCount',
            'avgOrder',
            'generatedAt',
            'generated_at', // Added this to satisfy line 121 of your Blade
            'categoryStats',
            'monthName',
            'year',
            'data',
            'summary'
        ));

        return $pdf->setPaper('a4')->download('Mavazi_Report_'.now()->format('d_M_Y').'.pdf');
    }

    public function feedbackIndex(){
        $feedbacks = Feedback::with(['sale.user'])->latest()->get();
        return view('admin.feedback.index', compact('feedbacks'));   
    }


}
