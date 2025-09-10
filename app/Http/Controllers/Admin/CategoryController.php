<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DataTables\CategoriesDataTable;
use App\Models\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'unique:categories'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Category::create($validated);
        return response()->json(['message' => 'Categoría creada exitosamente.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Category $category)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'unique:categories,id,' . $category->id],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);
        return response()->json(['message' => 'Categoría actualizada exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar la categoría. Hay productos asociados a esta categoría.'
            ], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada exitosamente.']);
    }
}
