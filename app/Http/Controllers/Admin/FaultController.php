<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFault;
use App\DataTables\FaultsDataTable;
use App\DataTables\FaultsAlertsDataTable;
use App\DataTables\FaultsHistoryDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaultController extends Controller
{
    public function index(FaultsAlertsDataTable $dataTable)
    {
        // Detect and register new faults
        $this->detectAndRegisterFaults();

        // Count metrics for cards
        $totalProducts = Product::count();
        $lowStockCount = ProductFault::pending()->lowStock()->count();
        $overStockCount = ProductFault::pending()->overStock()->count();

        return $dataTable->render('admin.faults.index', compact('totalProducts', 'lowStockCount', 'overStockCount'));
    }

    public function history(FaultsHistoryDataTable $dataTable)
    {
        return $dataTable->render('admin.faults.history');
    }

    public function configuration(FaultsDataTable $dataTable)
    {
        return $dataTable->render('admin.faults.configuration');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|min:0|gte:min_stock',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
        ]);

        return response()->json(['message' => 'Limites de stock actualizados correctamente.']);
    }

    public function markAsReviewed($id)
    {
        $fault = ProductFault::findOrFail($id);
        $fault->markAsReviewed(Auth::id());

        return response()->json([
            'message' => 'Falla marcada como revisada.',
            'success' => true
        ]);
    }

    public function createManual(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'fault_type' => 'required|in:low_stock,over_stock',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Create the fault with current product values
        ProductFault::create([
            'product_id' => $product->id,
            'fault_type' => $request->fault_type,
            'detected_at' => now(),
            'stock_at_detection' => $product->stock,
            'min_stock_at_detection' => $product->min_stock,
            'max_stock_at_detection' => $product->max_stock,
        ]);

        return response()->json([
            'message' => 'Falla creada exitosamente.',
            'success' => true
        ]);
    }

    /**
     * Detect and register faults for products with stock issues
     */
    private function detectAndRegisterFaults()
    {
        // Get products with low stock
        $lowStockProducts = Product::whereColumn('stock', '<', 'min_stock')->get();
        
        foreach ($lowStockProducts as $product) {
            // Check if there's already ANY fault for this product (pending or reviewed)
            // We only create a new fault if there's no existing fault at all
            $existingFault = ProductFault::where('product_id', $product->id)
                ->where('fault_type', 'low_stock')
                ->latest('detected_at')
                ->first();

            // Only create a new fault if:
            // 1. No fault exists at all, OR
            // 2. The latest fault was reviewed AND the stock has changed significantly
            if (!$existingFault) {
                ProductFault::create([
                    'product_id' => $product->id,
                    'fault_type' => 'low_stock',
                    'detected_at' => now(),
                    'stock_at_detection' => $product->stock,
                    'min_stock_at_detection' => $product->min_stock,
                    'max_stock_at_detection' => $product->max_stock,
                ]);
            } elseif ($existingFault->reviewed && $existingFault->stock_at_detection != $product->stock) {
                // If the fault was reviewed but the stock has changed, create a new fault
                ProductFault::create([
                    'product_id' => $product->id,
                    'fault_type' => 'low_stock',
                    'detected_at' => now(),
                    'stock_at_detection' => $product->stock,
                    'min_stock_at_detection' => $product->min_stock,
                    'max_stock_at_detection' => $product->max_stock,
                ]);
            }
        }

        // Get products with over stock
        $overStockProducts = Product::whereNotNull('max_stock')
            ->whereColumn('stock', '>', 'max_stock')
            ->get();
        
        foreach ($overStockProducts as $product) {
            $existingFault = ProductFault::where('product_id', $product->id)
                ->where('fault_type', 'over_stock')
                ->latest('detected_at')
                ->first();

            if (!$existingFault) {
                ProductFault::create([
                    'product_id' => $product->id,
                    'fault_type' => 'over_stock',
                    'detected_at' => now(),
                    'stock_at_detection' => $product->stock,
                    'min_stock_at_detection' => $product->min_stock,
                    'max_stock_at_detection' => $product->max_stock,
                ]);
            } elseif ($existingFault->reviewed && $existingFault->stock_at_detection != $product->stock) {
                ProductFault::create([
                    'product_id' => $product->id,
                    'fault_type' => 'over_stock',
                    'detected_at' => now(),
                    'stock_at_detection' => $product->stock,
                    'min_stock_at_detection' => $product->min_stock,
                    'max_stock_at_detection' => $product->max_stock,
                ]);
            }
        }
    }
}
