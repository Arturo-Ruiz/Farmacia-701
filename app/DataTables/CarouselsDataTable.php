<?php

namespace App\DataTables;

use App\Models\Carousel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CarouselsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('img', function ($carousel) {
                if ($carousel->img) {
                    return '  
            <div class="d-flex flex-column align-items-center p-3">  
                <div class="position-relative mb-2">  
                    <img src="' . $carousel->img_url . '" alt="Carousel #' . $carousel->id . '"  
                         class="carousel-image-preview shadow-sm"  
                         style="width: 400px; height: 110px; object-fit: cover; max-width: 100%;">  
                    <div class="position-absolute top-0 end-0 m-2">  
                        <span class="badge bg-primary">#' . $carousel->id . '</span>  
                    </div>  
                </div>  
                <span class="filename-badge">  
                    <i class="fas fa-file-image me-1"></i>' . $carousel->img . '  
                </span>  
            </div>  
        ';
                }
                return '  
        <div class="text-center p-4">  
            <i class="fas fa-image fa-3x text-muted mb-2"></i>  
            <p class="text-muted mb-0">Sin imagen</p>  
        </div>  
    ';
            })
            ->addColumn('action', function (Carousel $carousel) {
                return '  
        <div class="d-flex justify-content-center align-items-center gap-2 h-100">  
            <button class="btn btn-success edit-btn" data-id="' . $carousel->id . '">  
                <i class="fa-solid fa-pencil me-1"></i>Editar  
            </button>  

        </div>  
    ';
            })
    //         ->addColumn('action', function (Carousel $carousel) {
    //             return '  
    //     <div class="d-flex justify-content-center align-items-center gap-2 h-100">  
    //         <button class="btn btn-success edit-btn" data-id="' . $carousel->id . '">  
    //             <i class="fa-solid fa-pencil me-1"></i>Editar  
    //         </button>  
    //         <button class="btn btn-danger  delete-btn" data-id="' . $carousel->id . '">  
    //             <i class="fa-solid fa-trash me-1"></i>Eliminar  
    //         </button>  
    //     </div>  
    // ';
    //         })
            ->rawColumns(['img', 'action'])
            ->setRowId('id');
    }

    public function query(Carousel $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('created_at', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('carousels-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->parameters([
                'responsive' => true,
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
            Column::make('id')->title('ID')->width(80)->addClass('text-center align-middle'),
            Column::make('img')->title('Imagen')->orderable(false)->searchable(false),
            Column::computed('action')
                ->title('')
                ->exportable(true)
                ->printable(false)
                ->width(180)
                ->addClass('text-center align-middle'),
        ];
    }
}
