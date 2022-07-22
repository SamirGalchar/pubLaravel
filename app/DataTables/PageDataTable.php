<?php

namespace App\DataTables;

use App\Library\Projectfunction;
use App\Models\Page;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PageDataTable extends DataTable
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
            ->addColumn('action', 'admin.pages.action')->setRowId('id')
            /*->skipPaging()*/
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Page $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Page $model)
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
            ->setTableId('page-table')
            ->columns($this->getColumns())
            ->addColumnBefore(Projectfunction::indexColumn())
            ->minifiedAjax()
            ->addAction()
            ->dom('Bfrtip')
            ->orderBy(1,'desc')
            ->buttons(
                Button::make('create')->addClass('btn btn-success mx-2'),
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
            Column::make('id')->hidden(),
            Column::make('short_description')->width(900)->title('Page Name'),
            Column::make('title')->width(900)->title('Page Title')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Page_' . date('YmdHis');
    }
}
