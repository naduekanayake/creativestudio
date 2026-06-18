<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:super_admin,admin,staff',
            'phone'    => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($validated);

        ActivityLog::log('created', 'User', $user->id, $user->name,
            'New user created: ' . $user->name . ' (' . $user->role_label . ')',
            'users', 'blue');

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:super_admin,admin,staff',
            'phone'    => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        ActivityLog::log('updated', 'User', $user->id, $user->name,
            'User updated: ' . $user->name, 'users', 'orange');

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if ($user->id === $currentUserId) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::log('deleted', 'User', null, $name,
            'User deleted: ' . $name, 'users', 'red');

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleStatus(User $user)
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if ($user->id === $currentUserId) {
            return back()->with('error', 'You cannot deactivate your own account!');
        }

        $user->update(['is_active' => !$user->is_active]);

        ActivityLog::log('updated', 'User', $user->id, $user->name,
            'User status changed: ' . $user->name . ' → ' . ($user->is_active ? 'Active' : 'Inactive'),
            'users', 'orange');

        return back()->with('success', 'User status updated!');
    }
}