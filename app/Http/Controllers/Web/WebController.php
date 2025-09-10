<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Carousel;
use App\Models\Laboratory;
use App\Models\Ad;
use App\Models\Product;
use App\Models\DayRate;

class WebController extends Controller
{
    public function home()
    {
        $carousels = Carousel::orderBy('id', 'asc')->get();
        $laboratories = Laboratory::orderBy('id', 'asc')->get();
        $ads = Ad::orderBy('id', 'asc')->get();
        $dayRate = DayRate::latest()->first();

        $products = Product::with(['category', 'tax'])
            ->where('stock', '>', 0)
            ->orderBy('sales', 'desc')
            ->limit(12)
            ->get();

        return view('web.home', compact('carousels', 'laboratories', 'ads', 'products', 'dayRate'));
    }

    public function loadMoreProducts(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = 12;

        $products = Product::with(['category', 'tax'])
            ->where('stock', '>', 0)
            ->orderBy('sales', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $dayRate = DayRate::latest()->first();

        return response()->json([
            'products' => $products,
            'dayRate' => $dayRate,
            'hasMore' => Product::where('stock', '>', 0)->count() > ($offset + $limit)
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $priceOrder = $request->get('price_order');
        $offset = $request->get('offset', 0);
        $limit = 12;

        if (empty($query)) {
            return redirect()->route('web.home');
        }

        $carousels = Carousel::orderBy('id', 'asc')->get();
        $laboratories = Laboratory::orderBy('id', 'asc')->get();
        $ads = Ad::orderBy('id', 'asc')->get();
        $dayRate = DayRate::latest()->first();

        $productsQuery = Product::with(['category', 'tax'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('laboratory', 'LIKE', '%' . $query . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($query) {
                        $categoryQuery->where('name', 'LIKE', '%' . $query . '%');
                    });
            });

        // Aplicar ordenamiento por precio  
        if ($priceOrder === 'asc') {
            $productsQuery->orderBy('price', 'asc');
        } elseif ($priceOrder === 'desc') {
            $productsQuery->orderBy('price', 'desc');
        } else {
            $productsQuery->orderBy('sales', 'desc');
        }

        $products = $productsQuery->offset($offset)->limit($limit)->get();

        $totalResults = Product::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('laboratory', 'LIKE', '%' . $query . '%')
                ->orWhereHas('category', function ($categoryQuery) use ($query) {
                    $categoryQuery->where('name', 'LIKE', '%' . $query . '%');
                });
        })->count();

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'dayRate' => $dayRate,
                'hasMore' => $totalResults > ($offset + $limit)
            ]);
        }

        return view('web.search', compact('carousels', 'laboratories', 'ads', 'products', 'dayRate', 'query', 'totalResults', 'priceOrder'));
    }

    public function loadMoreSearchResults(Request $request)
    {
        $query = $request->get('q');
        $priceOrder = $request->get('price_order');
        $offset = $request->get('offset', 0);
        $limit = 12;

        if (empty($query)) {
            return response()->json(['products' => [], 'dayRate' => null, 'hasMore' => false]);
        }

        $productsQuery = Product::with(['category', 'tax'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('laboratory', 'LIKE', '%' . $query . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($query) {
                        $categoryQuery->where('name', 'LIKE', '%' . $query . '%');
                    });
            });

        // Aplicar ordenamiento por precio  
        if ($priceOrder === 'asc') {
            $productsQuery->orderBy('price', 'asc');
        } elseif ($priceOrder === 'desc') {
            $productsQuery->orderBy('price', 'desc');
        } else {
            $productsQuery->orderBy('sales', 'desc');
        }

        $products = $productsQuery->offset($offset)->limit($limit)->get();

        $totalResults = Product::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('laboratory', 'LIKE', '%' . $query . '%')
                ->orWhereHas('category', function ($categoryQuery) use ($query) {
                    $categoryQuery->where('name', 'LIKE', '%' . $query . '%');
                });
        })->count();

        $dayRate = DayRate::latest()->first();

        return response()->json([
            'products' => $products,
            'dayRate' => $dayRate,
            'hasMore' => $totalResults > ($offset + $limit)
        ]);
    }

    public function laboratory($keyword)
    {
        // Buscar el laboratorio por keyword  
        $laboratory = Laboratory::where('keyword', $keyword)->firstOrFail();

        // Obtener TODOS los productos del laboratorio ordenados por ventas (sin límite)  
        $products = Product::with(['category', 'tax'])
            ->where('laboratory', 'LIKE', '%' . $laboratory->keyword . '%')
            ->orderBy('sales', 'desc')
            ->get(); // Sin limit() para traer todos  

        $dayRate = DayRate::latest()->first();

        // Contar total de productos para mostrar en la vista  
        $totalProducts = $products->count();

        return view('web.laboratory', compact('laboratory', 'products', 'dayRate', 'totalProducts'));
    }

    public function cart()
    {
        $dayRate = DayRate::latest()->first();

        return view('web.cart', compact('dayRate'));
    }
}
