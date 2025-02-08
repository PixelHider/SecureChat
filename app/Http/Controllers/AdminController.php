<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Message;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function index()
    {
        $header = 'Admin Dashboard';
        $users = User::all();
        $totalUsers = User::all()->count();
        $pendingUsers = User::where('status', 'inactive')->get();
        $messages = Message::all();
        $totalMessages = Message::all()->count();
        $cases = Cases::all();
        $totalCases = Cases::all()->count();
        return view('admin.dashboard', compact('users', 'messages', 'cases', 'header', 'totalUsers', 'pendingUsers', 'totalMessages', 'totalCases'));
    }

    // Approve user

    public function approve_user($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();
        return redirect()->back()->with('success', 'User approved successfully!');
    }

    // admin profile

    public function edit()
    {
        $user = auth()->guard('admin')->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Hash the new password and update the admin record
        $admin->password = Hash::make($validated['password']);
        $admin->save(); // Save the admin model with the updated password

        // Log the admin out to avoid session conflicts
        Auth::guard('admin')->logout();

        return redirect()->route('admin.profile.edit')->with('success', 'Password updated successfully!');
    }


    public function destroy()
    {
        $admin = Auth::guard('admin')->user();

        // Check if there's more than one admin to prevent deleting the last admin
        if ($this->isLastAdmin($admin)) {
            return redirect()->route('admin.profile.edit')->with('error', 'You cannot delete the last admin!');
        }

        // verify the password
        if (!Hash::check(request('password'), $admin->password)) {
            return redirect()->back()->with('error', 'Incorrect password!');
        }

        // Delete the admin account
        $admin->delete();

        // Logout the admin after deletion to avoid session conflicts
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('success', 'Your account has been deleted successfully.');
    }

    private function isLastAdmin($admin)
    {
        // Check if this is the only admin left in the system
        return \App\Models\Admin::count() === 1;
    }

    // destroy user
    public function destroy_user($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    // destroy case
    public function destroy_case($id)
    {
        $case = Cases::findOrFail($id);
        $case->delete();
        return redirect()->back()->with('success', 'Case deleted successfully!');
    }

    // destroy message
    public function destroy_message($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        return redirect()->back()->with('success', 'Message deleted successfully!');
    }


    // logout admin

    public function logout(Request $request)
    {
        // Log out the admin user
        auth()->guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
