<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DataTables\DayRatesDataTable;
use App\Models\DayRate;

class DayRateController extends Controller
{
    public function index(DayRatesDataTable $dataTable)
    {
        return $dataTable->render('admin.day-rates.index');
    }

    public function show(DayRate $dayRate)
    {
        return response()->json($dayRate);
    }

    public function update(Request $request, DayRate $dayRate)
    {
        $validated = $request->validate([
            'value' => 'required|numeric|min:0'
        ]);

        $dayRate->update($validated);

        return response()->json(['message' => 'Tasa del día actualizada exitosamente.']);
    }
}
