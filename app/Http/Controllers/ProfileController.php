<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('profile');
    }

    public function updateNameAndEmail(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|min:5|max:255|unique:users,email,' . $userId,
            'current_password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }

        $user->name = $request->name;
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null; // Mark email as not verified
        }
        $user->save();

        return back()->with('status', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password_password' => 'The current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Password updated successfully');
    }

    public function updateUserAndPassword(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|min:5|max:255|unique:users,email,' . $userId,
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('status', 'Profile and password updated successfully');
    }
}