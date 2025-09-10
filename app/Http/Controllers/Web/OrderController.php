<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Sale;
use App\Models\Product;
use App\Models\DayRate;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function processOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'required|string|max:20',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:debit,credit,mobile,zelle,binance,paypal',
            'special_requests' => 'nullable|string',
            'cart_products' => 'required|array|min:1',
            'cart_products.*.id' => 'required|integer|exists:products,id',
            'cart_products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear o actualizar cliente  
            $client = Client::updateOrCreate(
                ['id_card' => $request->customer_id],
                [
                    'name' => $request->customer_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );

            // 2. Obtener tasa del día  
            $dayRate = DayRate::latest()->first();

            // 3. Procesar productos del carrito  
            $cartProducts = $request->cart_products;
            $totalAmount = 0;
            $processedProducts = [];

            foreach ($cartProducts as $cartItem) {
                $product = Product::find($cartItem['id']);

                if (!$product) {
                    throw new \Exception("Producto no encontrado: {$cartItem['id']}");
                }

                $quantity = (int) $cartItem['quantity'];

                // Verificar stock  
                if ($product->stock < $quantity) {
                    return response()->json([
                        'error' => "Stock insuficiente para: {$product->name}, Cantidad disponible: {$product->stock}, Cantidad solicitada: {$quantity}",
                        'product_name' => $product->name,
                        'available_stock' => $product->stock,
                        'requested_quantity' => $product['quantity']
                    ], 422);
                }

                // Actualizar stock y ventas  
                $product->decrement('stock', $quantity);
                $product->increment('sales', $quantity);

                // Calcular total  
                $itemTotal = $product->price * $quantity;
                $totalAmount += $itemTotal;

                // Guardar información del producto para la venta  
                $processedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                    'laboratory' => $product->laboratory,
                ];
            }

            // 4. Crear la venta  
            $sale = Sale::create([
                'client_id' => $client->id,
                'delivery_type' => $request->delivery_type,
                'payment_method' => $request->payment_method,
                'products' => $processedProducts,
                'product_request' => $request->special_requests,
                'day_rate_value' => $dayRate ? $dayRate->value : 1,
                'total_amount' => $totalAmount,
            ]);

            // 5. Incrementar número de compras del cliente  
            $client->increment('number_of_purchases');

            DB::commit();

            return $this->redirectToWhatsApp($client, $sale, $processedProducts, $dayRate);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function redirectToWhatsApp($client, $sale, $products, $dayRate)
    {
        $formattedProducts = [];
        foreach ($products as $product) {
            $unitPriceBs = $dayRate ? $product['price'] * $dayRate->value : $product['price'];
            $totalBs = $dayRate ? $product['total'] * $dayRate->value : $product['total'];

            $formattedProducts[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'total' => $product['total'],
                'laboratory' => $product['laboratory'],
                'unit_price_formatted' => $dayRate ?
                    "Bs. " . number_format($unitPriceBs, 2) . " | $" . number_format($product['price'], 2) :
                    "Bs. " . number_format($product['price'], 2),
                'total_formatted' => $dayRate ?
                    "Bs. " . number_format($totalBs, 2) . " | $" . number_format($product['total'], 2) :
                    "Bs. " . number_format($product['total'], 2),
            ];
        }

        $data = [
            'name' => $client->name,
            'id_card' => $client->id_card,
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'deliveryMethod' => $sale->delivery_type === 'pickup' ? 'Retiro en tienda' : 'Delivery',
            'paymentMethod' => $this->getPaymentMethodName($sale->payment_method),
            'total' => $dayRate ?
                "Bs. " . number_format($sale->total_amount * $dayRate->value, 2) . " | $" . number_format($sale->total_amount, 2) :
                "Bs. " . number_format($sale->total_amount, 2),
            'requestProducts' => $sale->product_request,
            'products' => $formattedProducts,
        ];

        return view('web.whatsapp-redirect', $data);
    }

    private function getPaymentMethodName($method)
    {
        $methods = [
            'debit' => 'Tarjeta de débito',
            'credit' => 'Tarjeta de crédito',
            'mobile' => 'Pago móvil',
            'zelle' => 'Zelle',
            'binance' => 'Binance',
            'paypal' => 'PayPal',
        ];

        return $methods[$method] ?? $method;
    }
}
