<?php

namespace App\DataTables;

use App\Models\Laboratory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LaboratoriesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Laboratory $laboratory) {
                return '  
                    <button class="btn btn-success edit-btn mt-3" data-id="' . $laboratory->id . '">   
                        <i class="fa-solid fa-pencil mr-2"></i> Editar  
                    </button>    
                    <button class="btn btn-danger delete-btn mt-3" data-id="' . $laboratory->id . '">   
                        <i class="fa-solid fa-trash mr-2"></i> Eliminar  
                    </button>  
                ';
            })
            ->addColumn('logo_display', function (Laboratory $laboratory) {
                if ($laboratory->logo) {
                    return '<img src="' . asset('storage/' . $laboratory->logo) . '" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;" class="rounded shadow-sm">';
                }
                return '<span class="text-muted">Sin logo</span>';
            })
            ->rawColumns(['action', 'logo_display'])
            ->setRowId('id');
    }

    public function query(Laboratory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('laboratories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
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
            Column::make('logo_display')->title('Logo')->searchable(false)->orderable(false),
            Column::make('keyword')->title('Palabra Clave'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center')
                ->title('Acciones'),
        ];
    }
}
