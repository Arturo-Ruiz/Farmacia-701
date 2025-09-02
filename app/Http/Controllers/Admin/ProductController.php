<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ProductsDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

use function Pest\Laravel\call;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('admin.products.index');
    }

    public function import(Request $request){

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport, filePath: $request->file('excel_file'));  
            return response()->json(['message' => 'Productos importados exitosamente.']);  
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al importar: ' . $e->getMessage()], 422);  
        }

    }
}
