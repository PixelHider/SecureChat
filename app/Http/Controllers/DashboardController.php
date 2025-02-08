<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $current_user = Auth::user()->role;
        // dd($current_user);

        if ($current_user == 'victim') {
            $profiles = User::whereIn('role', ['police', 'journalist'])->where('status' , 'active')->get();
            $recent_messages = Message::where('sender_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();
            
            $recent_cases = Cases::where('user_id', Auth::user()->id)->latest()->get();

            $role = Auth::user()->role;
            return view('victim.dashboard', compact('profiles', 'recent_messages', 'role', 'recent_cases'));
        }

        if($current_user == 'journalist') {
            $recent_messages = Message::where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();

            $recent_cases = Cases::where('case_to', 'journalist')->latest()->get();

            $role = Auth::user()->role;

            // dd($recent_messages);
            return view('dashboard', compact('recent_messages', 'role', 'recent_cases'));
        }

        if($current_user == 'police') {
            $recent_messages = Message::where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();

            $recent_cases = Cases::where('case_to', 'police')->latest()->get();

            $role = Auth::user()->role;
            return view('dashboard', compact('recent_messages', 'role', 'recent_cases'));
        }
    }
}
