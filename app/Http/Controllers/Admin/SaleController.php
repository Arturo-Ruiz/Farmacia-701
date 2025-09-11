<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\DataTables\SalesDataTable;
use Illuminate\Http\Request;


class SaleController extends Controller
{
    public function index(SalesDataTable $dataTable)
    {
        return $dataTable->render('admin.sales.index');
    }

    public function show($id)
    {
        $sale = Sale::with('client')->findOrFail($id);
        return response()->json($sale);
    }
}
