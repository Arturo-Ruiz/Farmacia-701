<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\DayRate;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // Métricas principales  
        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');
        $todaySalesCount = Sale::whereDate('created_at', today())->count();
        
        $totalClients = Client::count();
        $totalProducts = Product::count();

        // Ventas del mes actual  
        $monthlySales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Comparación con mes anterior  
        $lastMonthSales = Sale::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $salesGrowth = $lastMonthSales > 0 ?
            (($monthlySales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Ventas por día (últimos 30 días)  
        $dailySales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesByLaboratory = Sale::select('products')
            ->get()
            ->flatMap(function ($sale) {
                return collect($sale->products);
            })
            ->groupBy('laboratory')
            ->map(function ($products, $laboratory) {
                return [
                    'laboratory' => $laboratory ?: 'Sin laboratorio',
                    'total_sales' => $products->sum('total'),
                    'quantity_sold' => $products->sum('quantity')
                ];
            })
            ->sortByDesc('quantity_sold')
            ->take(20)
            ->values();

        // Top productos más vendidos  
        $topProducts = Sale::select('products')
            ->get()
            ->flatMap(function ($sale) {
                return collect($sale->products);
            })
            ->groupBy('name')
            ->map(function ($products) {
                return [
                    'name' => $products->first()['name'],
                    'quantity' => $products->sum('quantity'),
                    'total' => $products->sum('total')
                ];
            })
            ->sortByDesc('quantity')
            ->take(5);

        // Métodos de pago más usados  
        $paymentMethods = Sale::select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        // Tipos de entrega  
        $deliveryTypes = Sale::select('delivery_type', DB::raw('COUNT(*) as count'))
            ->groupBy('delivery_type')
            ->orderByDesc('count')
            ->get();

        // Tasa actual del día  
        $currentRate = DayRate::latest()->first();

        // Clientes más activos  
        $topClients = Client::withCount('sales')
            ->orderByDesc('sales_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'todaySalesCount',
            'totalClients',
            'totalProducts',
            'monthlySales',
            'salesGrowth',
            'dailySales',
            'topProducts',
            'paymentMethods',
            'deliveryTypes',
            'salesByLaboratory',
            'currentRate',
            'topClients'
        ));
    }
}
