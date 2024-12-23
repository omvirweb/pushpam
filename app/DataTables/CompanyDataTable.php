<?php

namespace App\DataTables;

use App\Models\Company;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
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
                    $editBtn = '<button onclick="editCompany(' . $row->id . ')" class="btn btn-warning btn-sm">Edit</button>';
                    $deleteBtn = ' <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fa fa-trash"></i> Delete
                    </button>';
                    return $editBtn . $deleteBtn;
                }
            )
            ->setRowClass(function (Company $data) {
                return 'row_index_' . $data->id;
            })->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\City $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Company $model)
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
            ->setTableId('companyTable')
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
            Column::make('name'),
            Column::make('code'),
        ];
    }
}
