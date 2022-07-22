<?php

namespace App\DataTables;

use App\Library\Projectfunction;
use App\Models\EmailTemplate;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmailTemplateDataTable extends DataTable
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
            ->addColumn('action', 'admin.email-template.action')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmailTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmailTemplate $model)
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
                    ->setTableId('email-template-table')
                    ->columns($this->getColumns())
                    ->addColumnBefore(Projectfunction::indexColumn())
                    ->minifiedAjax()
                    ->addAction()
                    ->dom('Bfrtip')
                    ->orderBy(1,'desc')
                    ->buttons(
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
            Column::make('id')->width(100)->title('ID')->hidden(),
            Column::make('emailKey')->width(200)->title('Email Key'),
            Column::make('subject')->width(200)->title('Subject'),
            Column::make('templates')->width(900)->title('Template'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'EmailTemplate_' . date('YmdHis');
    }
}
