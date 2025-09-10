<?php  
  
namespace App\DataTables;  
  
use App\Models\DayRate;  
use Yajra\DataTables\Services\DataTable;  
use Yajra\DataTables\Html\Column;  
use Illuminate\Database\Eloquent\Builder as QueryBuilder;  
use Yajra\DataTables\Html\Builder as HtmlBuilder;  
use Yajra\DataTables\EloquentDataTable;  
  
class DayRatesDataTable extends DataTable  
{  
    public function dataTable(QueryBuilder $query): EloquentDataTable  
    {  
        return (new EloquentDataTable($query))  
            ->addColumn('action', function (DayRate $dayRate) {  
                return '  
                    <button class="btn btn-success edit-btn mt-3" data-id="' . $dayRate->id . '"><i class="fa-solid fa-pencil mr-2"></i> Editar</button>  
                ';  
            })  
            ->setRowId('id');  
    }  
  
    public function query(DayRate $model): QueryBuilder  
    {  
        return $model->newQuery();  
    }  
  
    public function html(): HtmlBuilder  
    {  
        return $this->builder()  
            ->setTableId('day-rates-table')  
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
            Column::make('value')->title('Valor'),  
            Column::computed('action')  
                ->title('')  
                ->exportable(true)  
                ->printable(false)  
                ->width(150)  
                ->addClass('text-center'),  
        ];  
    }  
}