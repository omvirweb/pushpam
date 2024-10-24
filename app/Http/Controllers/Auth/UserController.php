<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules;



class UserController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
{
    // Validate the request input
    $validated = $request->validate([
        'current_password' => ['required', 'current_password'], // Validate the current password
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'password_confirmation' => 'required'
    ]);

    // Ensure the user is authenticated
    $user = Auth::user();

    if (!$user) {
        return back()->withErrors(['error' => 'User is not authenticated.']);
    }

    // Get the new password and the current hashed password
    $newPassword = $request->input('password');
    $currentHashedPassword = $user->password;

    // Check if the new password is the same as the current password
    if (Hash::check($newPassword, $currentHashedPassword)) {
        return back()->withErrors(['password' => 'The new password must be different from the current password.']);
    }

    // Update the user's password if validation passes
    $user->update([
        'password' => Hash::make($newPassword), // Hash the new password and save it
    ]);

    // Redirect back with success message
    return redirect()->back()->with('status', 'Password updated successfully.');
}


    // public function update(Request $request)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'current_password' => ['required', 'current_password'], // Validate current password
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     // Ensure the user is authenticated
    //     $user = Auth::user();

    //     if (!$user) {
    //         return back()->withErrors(['error' => 'User is not authenticated.']);
    //     }

    //     // Get the new password and the current hashed password
    //     $newPassword = $request->input('password');
    //     $currentHashedPassword = $user->password;

    //     // Manually compare the new password to the current password (hash the new password temporarily)
    //     if (Hash::check($newPassword, $currentHashedPassword)) {
    //         return back()->withErrors(['password' => 'The new password must be different from the current password.']);
    //     }

    //     // Update the password if it's different
    //     $user->update([
    //         'password' => Hash::make($newPassword),
    //     ]);

    //     return redirect()->back()->with('status', 'Password updated successfully.');
    // }

    // public function update(Request $request): JsonResponse
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'current_password' => ['required', 'current_password'],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     // Check if the current password matches
    //     if (!Hash::check($request->input('current_password'), Auth::user()->password)) {
    //         return response()->json([
    //             'errors' => [
    //                 'current_password' => ['The current password is incorrect.']
    //             ]
    //         ], 422);
    //     }

    //     // Check if the new password is the same as the current password
    //     if (Hash::check($request->input('password'), Auth::user()->password)) {
    //         return response()->json([
    //             'errors' => [
    //                 'password' => ['The new password must be different from the current password.']
    //             ]
    //         ], 422);
    //     }

    //     // Update the password
    //     Auth::user()->update([
    //         'password' => Hash::make($request->input('password')),
    //     ]);

    //     return response()->json(['message' => 'Password updated successfully.']);
    // }
}
