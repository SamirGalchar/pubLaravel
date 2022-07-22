<?php

namespace App\DataTables;

use App\Library\Projectfunction;
use App\Models\Membership;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MembershipDataTable extends DataTable
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
            /*->editColumn('price', function(Membership $property) {
                    return '$'. Projectfunction::priceFormat($property->price);
            })*/    
            ->addColumn('action', 'admin.membership.action')->setRowId('id')
            /*->skipPaging()*/
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Membership $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Membership $model)
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
            ->setTableId('membership-table')
            ->columns($this->getColumns())
            ->addColumnBefore(Projectfunction::indexColumn())
            ->minifiedAjax()
            ->addAction()
            ->dom('Bfrtip')
            ->orderBy(1,'desc')
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
            Column::make('id')->width(100)->title('ID')->hidden(),
            Column::make('name')->title('Plan Name'),
            Column::make('sort_description')->title('Sort Description'),
            Column::make('price'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Membership_' . date('YmdHis');
    }
}
