<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use App\Library\Projectfunction;
use App\Models\Video;
use DB;

class VideoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'admin.videos.action')->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VideoDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Video $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('videos-table')
            ->columns($this->getColumns())
            ->addColumnBefore(Projectfunction::indexColumn())
            ->minifiedAjax()
            ->addAction()
            ->dom('Bfrtip')
            ->orderBy(1,'asc')
            ->buttons(
                //Button::make('create')->addClass('btn btn-success mx-2'),
                Button::make('reset')->addClass('mx-1 btn btn-danger')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID')->hidden(),
            Column::make('name')->title('Name'),
            Column::make('link')->title('Video'),
            Column::make('phase')->title('Phase'),
            Column::make('free_trail')->title('Free'),
            Column::make('status')->title('Status'),            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Video_' . date('YmdHis');
    }
}
