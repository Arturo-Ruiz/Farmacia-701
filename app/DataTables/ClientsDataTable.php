<?php

namespace App\DataTables;

use App\Models\Client;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\EloquentDataTable;


class ClientsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Client $client) {
                return '  
                    <a href="' . route('admin.clients.purchases', $client->id) . '" class="btn btn-primary view-btn mt-3">  
                        <i class="fa-solid fa-cart-shopping me-2"></i>Compras  
                    </a>  
                    <button class="btn btn-success edit-btn mt-3" data-id="' . $client->id . '">   
                        <i class="fa-solid fa-pencil mr-2"></i> Editar  
                    </button>  
                ';
            })
            ->setRowId('id');
    }

    public function query(Client $model): QueryBuilder
    {
        return $model->newQuery()->withCount('sales');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('clients-table')
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
            Column::make('name')->title('Nombre'),
            Column::make('id_card')->title('Cédula/RIF'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Teléfono'),
            Column::make('number_of_purchases')->title('Compras'),
            Column::computed('action')
                ->title('')
                ->exportable(true)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }
}
