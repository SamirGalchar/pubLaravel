<?php

namespace App\DataTables;

use App\Library\Projectfunction;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use DB;

class UserDataTable extends DataTable
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
            ->addColumn('action', 'admin.user.action')->setRowId('id')
            ->escapeColumns(['name'])
            /*->editColumn('name',function (User $data){
                return $data->first_name.' '.$data->last_name;
            })*/
            /*->skipPaging()*/
            /*->filterColumn('name',function ($query, $keyword){
                $query->whereRaw("CONCAT(".DB::getTablePrefix()."users.first_name, ' ', ".DB::getTablePrefix()."users.last_name) like ?", ["%{$keyword}%"]);
            })*/
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()
                ->select(['id','name','email','phone','isActive','isPaid'])
                ->where('role','user');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('id')->title('ID')->hidden(),
            Column::make('name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('isPaid')->title('Subscribed'),
            Column::make('isActive')->title('status'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}
