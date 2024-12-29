<?php

namespace App\DataTables;

use App\Models\FleetFile;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FleetFileDataTable extends DataTable
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
            ->addColumn(
                'action',
                function ($row) {
                    $deleteBtn = ' <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fa fa-trash"></i> Delete
                    </button>';
                    return $deleteBtn;
                }
            )
            ->editColumn(
                'type',
                function ($row) {
                    return $row->typeData->name ?? ($row->type ?? '-');
                }
            )
            ->editColumn(
                'company',
                function ($row) {
                    return $row->company->name ?? '-';
                }
            )
            ->setRowClass(function (FleetFile $data) {
                return 'row_index_' . $data->id;
            })->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\City $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(FleetFile $model)
    {
        return $model->newQuery()->with(['typeData', 'company'])->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('FleetFileTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action'),
            Column::make('type'),
            Column::make('company'),
        ];
    }
}
