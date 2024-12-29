<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('users.index', ['companies' => Company::all()]);
    }

    public function store(Request $request)
    {
        if ($request->has('id') && !empty($request->id)) {
            // Edit Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $request->id,
                'password' => 'nullable|string|min:8|confirmed',
                'allowed_company' => 'required|array',
            ]);
        } else {
            // Add Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $request->id,
                'password' => 'required|string|min:8|confirmed',
                'allowed_company' => 'required|array',
            ]);
        }
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $data = $validator->validated();
        if (!$request->filled('password')) {
            unset($data['password']);
        }
        $user = User::updateOrCreate(
            ['id' => $request->id],
            $data
        );
        $user->companies()->sync($data['allowed_company']);
        return response()->json(['message' => 'User saved successfully!']);
    }

    public function edit(User $user)
    {
        $user->load('companies');
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'allowed_company' => $user->companies->pluck('id'),
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user!'], 500);
        }
    }
}
