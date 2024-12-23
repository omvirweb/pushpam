<?php

namespace App\Http\Controllers;

use App\DataTables\CompanyDataTable;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $dataTable = new CompanyDataTable();
        return $dataTable->render('companies.index');
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:companies,code,' . $request->id,
            'name' => 'required|string|max:255',
        ]);
        Company::updateOrCreate(
            ['id' => $request->id],
            [
                'code' => $validated['code'],
                'name' => $validated['name'],
            ]
        );
        return response()->json(['message' => 'Company saved successfully!']);
    }

    public function edit(Company $company)
    {
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'company_code' => 'required|unique:companies,company_code,' . $company->id,
            'company_name' => 'required',
        ]);

        $company->update($request->all());
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return response()->json(['message' => 'Company deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete company!'], 500);
        }
    }
}
