<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MessageController extends Controller
{
    public function show($username)
    {
        $recipient = User::where('username', $username)->firstOrFail();
        // dd($recipient->username);
        return view('send-message', compact('recipient'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required|integer|exists:users,id',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $message = new Message();
        if ($request->sender_id != Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $request->receiver_id;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();

        return redirect()->route('dashboard')->with('success', 'Message sent successfully!');
    }

    public function msg_for_police()
    {
        // Check if the authenticated user is not a police officer
        if (Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized action');
        }

        // Get messages where the logged-in police officer is either the sender or receiver
        $messages = Message::with(['sender', 'receiver']) // Eager load sender and receiver
            ->where('receiver_id', Auth::id()) // Assuming the police will be the receiver
            ->orWhere('sender_id', Auth::id()) // Or the police will be the sender
            ->latest() // Sort by latest messages
            ->get();

        return view('messages.index', compact('messages'));
    }

    public function show_msg_for_police($id)
    {
        $role = Auth::user()->role;
        // Check if the authenticated user is not a police officer
        if (Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized action');
        }

        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Ensure the authenticated user is the receiver of the message
        if ($message->receiver_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Mark the message as read
        $message->is_read = 1;  // Correct assignment of is_read
        $message->save();

        // Get replies for the message
        $replies = Reply::where('message_id', $id)->get();

        // dd($replies);

        // Return the view with the message data
        return view('messages.show', compact('message', 'replies', 'role'));
    }

    public function replyPolice(Request $request, $id)
    {
        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Ensure the authenticated user is the receiver of the message
        if ($message->receiver_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Validate the reply message
        $validated = $request->validate([
            'replyMessage' => 'required|string|max:1000', // Optional: Adjust validation as needed
        ]);

        // dd($validated);

        // Create a new reply message
        Reply::create([
            'message_id' => $message->id,
            'receiver_id' => $message->sender_id,
            'sender_id' => Auth::id(),
            'replyMessage' => $validated['replyMessage'],  // Corrected key name
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    public function msg_for_journalist()
    {
        // dd(Auth::user()->role);
        // Check if the authenticated user is not a journalist
        if (Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized action');
        }

        // Get messages where the logged-in journalist is the sender
        $messages = Message::with(['sender', 'receiver'])
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->latest()
            ->get();

        // dd($messages);

        return view('messages.index', compact('messages'));
    }

    public function show_msg_for_journalist($id)
    {
        $role = Auth::user()->role;
        // Check if the authenticated user is not a journalist
        if (Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized action');
        }
        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Ensure the authenticated user is the receiver of the message
        if ($message->receiver_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Mark the message as read 
        $message->is_read = 1;  // Correct assignment of is_read
        $message->save();

        // Get replies for the message
        $replies = Reply::where('message_id', $id)->get();

        // Return the view with the message data
        return view('messages.show', compact('message', 'replies', 'role'));
    }

    public function replyJournalist(Request $request, $id)
    {
        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Ensure the authenticated user is the sender of the message
        if ($message->receiver_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Validate the reply message
        $validated = $request->validate([
            'replyMessage' => 'required|string|max:1000', // Optional: Adjust validation as needed
        ]);

        // Create a new reply message
        Reply::create([
            'message_id' => $message->id,
            'receiver_id' => $message->receiver_id,
            'sender_id' => Auth::id(),
            'replyMessage' => $validated['replyMessage'],  // Corrected key name
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    // Msg for victim
    public function msg_for_victim()
    {
        // dd(Auth::user()->role);
        // Check if the authenticated user is not a victim
        if (Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized action');
        }

        // Get messages where the logged-in victim is the sender
        $messages = Message::with(['sender', 'receiver'])
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->latest()
            ->get();

        // dd($messages);

        return view('messages.index', compact('messages'));
    }

    // show msg for victim
    public function show_msg_for_victim($id)
    {
        $role = Auth::user()->role;
        // Check if the authenticated user is not a victim
        if (Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized action');
        }

        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Get replies for the message
        $replies = Reply::where('message_id', $id)->get();

        // Return the view with the message data
        return view('messages.show', compact('message', 'replies', 'role'));
    }

    public function replyVictim(Request $request, $id)
    {
        // Find the message or fail if not found
        $message = Message::findOrFail($id);

        // Ensure the authenticated user is the receiver of the message
        if ($message->sender_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Validate the reply message
        $validated = $request->validate([
            'replyMessage' => 'required|string|max:1000', // Optional: Adjust validation as needed
        ]);

        // Create a new reply message
        Reply::create([
            'message_id' => $message->id,
            'receiver_id' => $message->sender_id,
            'sender_id' => Auth::id(),
            'replyMessage' => $validated['replyMessage'],  // Corrected key name
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    // Destroy Message
    public function destroyMsg($id)
    {
        $message = Message::findOrFail($id);
        // Check if the authenticated user is the sender or receiver of the message
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }
        $message->delete();
        return redirect()->back()->with('success', 'Message deleted successfully!');
    }
}
