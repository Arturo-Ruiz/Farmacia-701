<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\EloquentDataTable;


class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('category_name', function (Product $product) {
                return $product->category ? $product->category->name : 'N/A';
            })
            ->addColumn('tax_name', function (Product $product) {
                return $product->tax ? $product->tax->name : 'N/A';
            })
            ->addColumn('medical_prescription_text', function (Product $product) {
                return $product->medical_prescription ? 'Sí' : 'No';
            })
            ->orderColumn('category_name', function ($query, $order) {
                return $query->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $order);
            })
            ->orderColumn('tax_name', function ($query, $order) {
                return $query->join('taxes', 'products.tax_id', '=', 'taxes.id')
                    ->orderBy('taxes.name', $order);
            })
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->whereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('tax_name', function ($query, $keyword) {
                $query->whereHas('tax', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with(['category', 'tax']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->parameters([
                'language' => [
                    'sProcessing'     => 'Procesando...',
                    'sLengthMenu'     => 'Mostrando _MENU_ registros',
                    'sZeroRecords'    => 'No se encontraron resultados',
                    'sEmptyTable'     => 'Ningún dato disponible en esta tabla',
                    'sInfo'           => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                    'sInfoEmpty'      => 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'sInfoFiltered'   => '(filtrado de un total de _MAX_ registros)',
                    'sSearch'         => 'Buscar:',
                    'sLoadingRecords' => 'Cargando...',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Nombre'),
            Column::make('laboratory')->title('Laboratorio'),
            // Column::computed('category_name')->title('Categoría')->orderable(true)->searchable(true),
            // Column::computed('tax_name')->title('Impuesto')->orderable(true)->searchable(true),
            Column::make('price')->title('Precio'),
            Column::make('stock')->title('Stock'),
            // Column::computed('medical_prescription_text')->title('Recipe'),
            Column::make('sales')->title('Ventas'),
            Column::make('img')->title('Imagen'),
        ];
    }
}
