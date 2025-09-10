<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DataTables\TaxesDataTable;
use App\Models\Tax;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxesDataTable $dataTable)
    {
        return $dataTable->render('admin.taxes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        Tax::create($validated);
        return response()->json(['message' => 'Impuesto creado exitosamente.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        return response()->json($tax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        $tax->update($validated);
        return response()->json(['message' => 'Impuesto actualizado exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        if ($tax->products()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar el impuesto. Hay productos asociados a este impuesto.'
            ], 422);
        }

        $tax->delete();
        return response()->json(['message' => 'Impuesto eliminado exitosamente.']);
    }
}
