<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-sm btn-primary" onclick="editUser(' . $row->id . ')">Edit</button>';
                $deleteBtn = ' <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="fa fa-trash"></i> Delete</button>';
                return $editBtn . $deleteBtn;
            })
            ->editColumn('username', function ($row) {
                return $row->username ?? '-';
            })
            ->addColumn('allowed_companies', function ($row) {
                return $row->companies->pluck('name')->implode(', ');
            });
    }

    public function query(User $model)
    {
        return $model->newQuery()->with('companies');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('userTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'action', 'name' => 'action', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'username', 'name' => 'username', 'title' => 'Username'],
            ['data' => 'allowed_companies', 'name' => 'companies.name', 'title' => 'Allowed Companies', 'searchable' => false, 'orderable' => false],
        ];
    }
}
