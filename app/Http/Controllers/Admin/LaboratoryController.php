<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DataTables\LaboratoriesDataTable;
use App\Models\Laboratory;
use Illuminate\Support\Facades\Storage;


class LaboratoryController extends Controller
{
    public function index(LaboratoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.laboratories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'keyword' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('laboratories', 'public');
        }

        Laboratory::create($validated);
        return response()->json(['message' => 'Laboratorio creado exitosamente.']);
    }

    public function show(Laboratory $laboratory)
    {
        return response()->json($laboratory);
    }

    public function update(Request $request, Laboratory $laboratory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'keyword' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe  
            if ($laboratory->logo) {
                Storage::disk('public')->delete($laboratory->logo);
            }
            $validated['logo'] = $request->file('logo')->store('laboratories', 'public');
        }

        $laboratory->update($validated);
        return response()->json(['message' => 'Laboratorio actualizado exitosamente.']);
    }

    public function destroy(Laboratory $laboratory)
    {
        // Eliminar logo si existe  
        if ($laboratory->logo) {
            Storage::disk('public')->delete($laboratory->logo);
        }

        $laboratory->delete();
        return response()->json(['message' => 'Laboratorio eliminado exitosamente.']);
    }
}
