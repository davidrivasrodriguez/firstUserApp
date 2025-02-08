<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdministratorsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class);
        $this->middleware(SuperAdminMiddleware::class)->only('indexSuper');
    }

    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function indexSuper()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|string|in:user,admin,superadmin'
    ]);

    try {

        $user->name = $request->name;
        $user->email = $request->email;


        if (Auth::user()->role == 'superadmin' || 
            (Auth::user()->role == 'admin' && $user->role != 'superadmin')) {
            $user->role = $request->role;
        }


        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');

    } catch (\Exception $e) {
        \Log::error('Error updating user', [
            'error' => $e->getMessage(),
            'user_id' => $user->id
        ]);
        
        return back()->withInput()
            ->withErrors(['error' => 'Error updating user: ' . $e->getMessage()]);
    }
}

    public function destroy(User $user)
    {
        if ($user->id != 1) {
            $user->delete();
        }
        return redirect()->route('admin.users.index');
    }
}