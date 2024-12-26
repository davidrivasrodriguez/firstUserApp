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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    

        if ($request->has('remove_photo') && $request->remove_photo == 1) {
            if ($user->profile_photo) {
                $oldPhotoPath = storage_path('app/profileImages/' . $user->profile_photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $user->profile_photo = null;
        }
        
        if ($request->hasFile('profile_photo')) {
            $imageName = time().'.'.$request->profile_photo->extension();
            $filePath = $request->profile_photo->storeAs('profileImages', $imageName);
        
            $fullPath = storage_path('app/' . $filePath);
            if (file_exists($fullPath)) {
                chmod($fullPath, 0644); 
                chown($fullPath, 'www-data'); 
            }
        
            if ($user->profile_photo) {
                $oldPhotoPath = storage_path('app/profileImages/' . $user->profile_photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        
            $user->profile_photo = $imageName;
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return redirect()->route('admin.users.index');
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