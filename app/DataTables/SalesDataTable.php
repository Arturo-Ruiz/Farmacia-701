<?php

namespace App\DataTables;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Sale $sale) {
                return '  
                <button class="btn btn-primary view-btn mt-3" data-id="' . $sale->id . '">  
                    <i class="fa-solid fa-eye mr-2"></i> Ver Detalles  
                </button>  
            ';
            })
            ->addColumn('client_name', function (Sale $sale) {
                return $sale->client ? $sale->client->name : 'Cliente eliminado';
            })
            ->addColumn('payment_method_formatted', function (Sale $sale) {
                switch ($sale->payment_method) {
                    case 'debit':
                        return 'Tarjeta de débito';
                    case 'credit':
                        return 'Tarjeta de crédito';
                    case 'mobile':
                        return 'Pago móvil';
                    case 'zelle':
                        return 'Zelle';
                    case 'binance':
                        return 'Binance';
                    case 'paypal':
                        return 'PayPal';
                    default:
                        return $sale->payment_method;
                }
            })
            ->addColumn('delivery_type_formatted', function (Sale $sale) {
                return $sale->delivery_type == 'pickup' ? 'Retiro en tienda' : 'Delivery';
            })
            ->addColumn('total_combined', function (Sale $sale) {
                $totalBs = $sale->total_amount * $sale->day_rate_value;
                return '<strong>Bs. ' . number_format($totalBs, 2) . '</strong> | $ ' . number_format($sale->total_amount, 2);
            })
            ->filterColumn('client_name', function ($query, $keyword) {
                $query->whereHas('client', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('payment_method_formatted', function ($query, $keyword) {
                $query->where('payment_method', 'like', "%{$keyword}%");
            })
            ->filterColumn('delivery_type_formatted', function ($query, $keyword) {
                $query->where('delivery_type', 'like', "%{$keyword}%");
            })
            ->editColumn('created_at', function (Sale $sale) {
                return $sale->created_at->format('d/m/Y H:i');
            })
            ->rawColumns(['action', 'total_combined'])
            ->setRowId('id');
    }

    public function query(Sale $model): QueryBuilder
    {
        return $model->newQuery()->with('client');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sales-table')
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::computed('client_name')->title('Cliente')->searchable(true),
            Column::computed('payment_method_formatted')->title('Método de Pago')->searchable(true),
            Column::computed('delivery_type_formatted')->title('Tipo de Entrega')->searchable(true),
            Column::computed('total_combined')->title('Total')->searchable(false),
            Column::computed('day_rate_value')->title('Tasa del Día'),
            Column::computed('created_at')->title('Fecha')->searchable(true),
            Column::computed('action')
                ->title('')
                ->exportable(true)
                ->printable(false)
                ->width(150)
                ->addClass('text-center')
                ->searchable(false),
        ];
    }
}
