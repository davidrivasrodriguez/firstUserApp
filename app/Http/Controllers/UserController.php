<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
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
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        if (Auth::user()->role == 'admin' && $user->role == 'superadmin') {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'You cannot edit the superadmin.']);
        }

        return view('users.edit', compact('user'));
    }


        public function update(Request $request, User $user)
        {
            try {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                    'password' => 'nullable|string|min:8|confirmed',
                    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'role' => 'nullable|string|in:user,admin,superadmin',
                ]);
        
                $user->name = $request->name;
                $user->email = $request->email;
                
                if ($request->has('role') && auth()->user()->role == 'superadmin') {
                    $user->role = $request->role;
                }
            
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);

                    $user->email_verified_at = null;

                    $user->sendEmailVerificationNotification();
                }
            

                $user->save();
        
                return redirect()->route('admin.users.index')
                    ->with('success', 'Usuario actualizado correctamente');
        
            } catch (\Exception $e) {
                \Log::error('Error actualizando usuario', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
                
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
            }
        }

    public function destroy(User $user)
    {
        if ($user->id == Auth::id()) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'You cannot delete yourself.']);
        }

        if ($user->id != 1) {
            $user->delete();
        }
        return redirect()->route('admin.users.index');
    }
}