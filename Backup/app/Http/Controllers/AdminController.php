<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * AdminController - Handles admin user management functionality
 */
class AdminController extends Controller
{
    /**
     * Display all users management page
     */
    public function users()
    {
        // Get all users with status (for admin visibility)
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new user
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'nic_number' => ['nullable', 'string', 'max:20', 'unique:users,nic_number'],
            'role' => ['required', 'in:admin,user1,user2,user3'],
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Edit user role and details
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update user role and details
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'nic_number' => ['nullable', 'string', 'max:20', 'unique:users,nic_number,' . $user->id],
            'role' => ['required', 'in:admin,user1,user2,user3'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Display user details
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Toggle user status between active and inactive
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent toggling your own status
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot change your own status!');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User status updated successfully!');
    }
}
