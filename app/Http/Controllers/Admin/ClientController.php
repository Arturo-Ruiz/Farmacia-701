<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Sale;
use App\Models\DayRate;
use App\DataTables\ClientsDataTable;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(ClientsDataTable $dataTable)
    {
        return $dataTable->render('admin.clients.index');
    }

    public function show(Client $client)
    {
        return response()->json($client->load('sales'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_card' => 'sometimes|required|string|unique:clients,id_card,' . $client->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $client->update($request->only(['name', 'id_card', 'email', 'phone', 'address']));

        return response()->json(['message' => 'Cliente actualizado exitosamente']);
    }

    public function showPurchases(Client $client, Request $request)
    {
        $query = $client->sales()->with(['client']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(5);
        
        $dayRate = DayRate::latest()->first();

        return view('admin.clients.purchases', compact('client', 'sales', 'dayRate'));
    }
}
