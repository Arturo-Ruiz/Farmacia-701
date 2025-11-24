<?php

namespace App\DataTables;

use App\Models\ProductFault;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\EloquentDataTable;

class FaultsHistoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image', function (ProductFault $fault) {
                return '<img src="' . $fault->product->img_url . '" alt="' . $fault->product->name . '" class="avatar avatar-sm border-radius-lg shadow">';
            })
            ->editColumn('product_name', function (ProductFault $fault) {
                return $fault->product->name;
            })
            ->editColumn('laboratory', function (ProductFault $fault) {
                return $fault->product->laboratory;
            })
            ->addColumn('fault_status', function (ProductFault $fault) {
                if ($fault->fault_type === 'low_stock') {
                    return '<span class="badge badge-sm bg-gradient-danger">STOCK BAJO</span>';
                } else {
                    return '<span class="badge badge-sm bg-gradient-warning">EXCESO</span>';
                }
            })
            ->editColumn('stock_at_detection', function (ProductFault $fault) {
                if ($fault->fault_type === 'low_stock') {
                    return '<span class="text-danger font-weight-bold">' . $fault->stock_at_detection . '</span>';
                } else {
                    return '<span class="text-warning font-weight-bold">' . $fault->stock_at_detection . '</span>';
                }
            })
            ->editColumn('detected_at', function (ProductFault $fault) {
                return $fault->detected_at->format('d/m/Y H:i');
            })
            ->addColumn('review_status', function (ProductFault $fault) {
                if ($fault->reviewed) {
                    return '<span class="badge badge-sm bg-gradient-success">REVISADA</span>';
                } else {
                    return '<span class="badge badge-sm bg-gradient-secondary">PENDIENTE</span>';
                }
            })
            ->addColumn('reviewed_info', function (ProductFault $fault) {
                if ($fault->reviewed && $fault->reviewedBy) {
                    return '<div class="text-xs">
                        <strong>' . $fault->reviewedBy->name . '</strong><br>
                        <span class="text-muted">' . $fault->reviewed_at->format('d/m/Y H:i') . '</span>
                    </div>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->rawColumns(['image', 'fault_status', 'stock_at_detection', 'review_status', 'reviewed_info'])
            ->setRowId('id');
    }

    public function query(ProductFault $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['product', 'reviewedBy'])
            ->orderBy('detected_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('faults-history-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->parameters([
               'language' => [
                    'sProcessing'     => 'Procesando...',
                    'sLengthMenu'     => 'Mostrando _MENU_ registros',
                    'sZeroRecords'    => 'No se encontraron resultados',
                    'sEmptyTable'     => 'No hay fallas registradas',
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
            Column::computed('image')->title('')->searchable(false)->orderable(false)->addClass('text-center')->width(60),
            Column::make('product_name')->title('Producto')->name('product.name'),
            Column::make('laboratory')->title('Laboratorio')->addClass('text-center')->name('product.laboratory'),
            Column::make('stock_at_detection')->title('Stock')->addClass('text-center font-weight-bold'),
            Column::make('min_stock_at_detection')->title('Mín')->addClass('text-center'),
            Column::make('max_stock_at_detection')->title('Máx')->addClass('text-center'),
            Column::computed('fault_status')->title('Tipo')->addClass('text-center')->searchable(false)->orderable(false),
            Column::make('detected_at')->title('Detectada')->addClass('text-center'),
            Column::computed('review_status')->title('Estado')->addClass('text-center')->searchable(false)->orderable(false),
            Column::computed('reviewed_info')->title('Revisado por')->addClass('text-center')->searchable(false)->orderable(false),
        ];
    }
}
