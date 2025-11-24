<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\EloquentDataTable;

class FaultsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('min_stock_input', function (Product $product) {
                return '<input type="number" min="0" class="form-control form-control-sm min-stock-input" data-id="' . $product->id . '" value="' . $product->min_stock . '" style="width: 80px;">';
            })
            ->addColumn('max_stock_input', function (Product $product) {
                return '<input type="number" min="0" class="form-control form-control-sm max-stock-input" data-id="' . $product->id . '" value="' . $product->max_stock . '" style="width: 80px;">';
            })
            ->addColumn('action', function (Product $product) {
                return '<button class="btn btn-success save-btn mt-3" data-id="' . $product->id . '"><i class="fas fa-save"></i> Guardar</button>';
            })
            ->rawColumns(['min_stock_input', 'max_stock_input', 'action'])
            ->setRowId('id');
    }

    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('faults-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Código'),
            Column::make('name')->title('Producto'),
            Column::make('laboratory')->title('Laboratorio'),
            Column::make('stock')->title('Stock Actual'),
            Column::computed('min_stock_input')->title('Stock Mínimo')->addClass('text-center'),
            Column::computed('max_stock_input')->title('Stock Máximo')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->addClass('text-center'),
        ];
    }
}
