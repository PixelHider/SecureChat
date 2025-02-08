<?php

namespace App\Http\Controllers;

use App\Models\CaseReply;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    public function cases_for_victim() {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('user_id', Auth::user()->id)->get();
        // dd($cases);

        return view('cases.index', compact('cases'));
    }

    public function createCase() {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        return view('cases.create');
    }

    public function storeCase(Request $request) {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'case_to' => 'required|in:police,journalist',
        ]);

        // dd($request->all());

        Cases::create([
            'case_number' => Str::random(10),
            'user_id' => Auth::user()->id,
            'case_to' => $request->case_to,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('cases.for.victim')->with('success', 'Case created successfully!');
    }

    public function show_case_for_victim($id) {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $case = Cases::where('user_id', Auth::user()->id)->findOrFail($id);
        $replies = CaseReply::where('case_id', $id)
        ->where('receiver_id', Auth::user()->id)
        ->orWhere('sender_id', Auth::user()->id)
        ->latest()
        ->get();
        $role = Auth::user()->role;
        return view('cases.show', compact('case', 'replies', 'role'));
    }

    // Cases for police
    public function cases_for_police() {
        if(Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('case_to', 'police')->get();
        return view('cases.index', compact('cases'));
    }

    public function show_case_for_police($id) {
        if(Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized');
        }
        $case = Cases::where('case_to', 'police')->findOrFail($id);
        $replies = CaseReply::where('case_id', $id)
        ->latest()
        ->get();
        // dd($replies);
        $role = Auth::user()->role;
        return view('cases.show', compact('case', 'replies', 'role'));
    }

    // Cases for journalist
    public function cases_for_journalist() {
        if(Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('case_to', 'journalist')->get();
        return view('cases.index', compact('cases'));
    }

    public function show_case_for_journalist($id) {
        if(Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized');
        }
        $case = Cases::where('case_to', 'journalist')->findOrFail($id);
        $replies = CaseReply::where('case_id', $id)
        ->latest()
        ->get();
        $role = Auth::user()->role;
        return view('cases.show', compact('case', 'replies', 'role'));
    }

    //reply case for police
    public function reply_case_for_police(Request $request, $id) {
        if(Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'replyMessage' => 'required',
        ]);
        $case = Cases::where('case_to', 'police')->findOrFail($id);

        CaseReply::create([
            'case_id' => $case->id,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $case->user_id,
            'replyMessage' => $request->replyMessage,
        ]);
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    //reply case for journalist
    public function reply_case_for_journalist(Request $request, $id) {
        if(Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'replyMessage' => 'required',
        ]);

        $case = Cases::where('case_to', 'journalist')->findOrFail($id);
        CaseReply::create([
            'case_id' => $case->id,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $case->user_id,
            'replyMessage' => $request->replyMessage,
        ]);
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    // reply case for victim
    public function reply_case_for_victim(Request $request, $id) {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'replyMessage' => 'required',
        ]);

        $case = Cases::where('user_id', Auth::user()->id)->findOrFail($id);
        CaseReply::create([
            'case_id' => $case->id,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $case->user_id,
            'replyMessage' => $request->replyMessage,
        ]);
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    // destroy case for victim
    public function destroy_case_for_victim($id) {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }

        $case = Cases::where('user_id', Auth::user()->id)->findOrFail($id);
        $case->delete();
        return redirect()->route('cases.for.victim')->with('success', 'Case deleted successfully!');
    }

    // close case for victim
    public function close_case_for_victim($id) {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $case = Cases::where('user_id', Auth::user()->id)->findOrFail($id);
        if ($case->status === 'closed') {
            return redirect()->back()->with('error', 'Case already closed!');
        }
        $case->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Case closed successfully!');
    }

    // closed case for police
    public function closed_case_for_police() {
        if(Auth::user()->role !== 'police') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('case_to', 'police')->where('status', 'closed')->get();
        $header_title = 'Closed Cases';
        return view('cases.index', compact('cases', 'header_title'));
    }

    // closed case for journalist
    public function closed_case_for_journalist() {
        if(Auth::user()->role !== 'journalist') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('case_to', 'journalist')->where('status', 'closed')->get();
        $header_title = 'Closed Cases';
        return view('cases.index', compact('cases', 'header_title'));
    }

    // closed case for victim
    public function closed_case_for_victim() {
        if(Auth::user()->role !== 'victim') {
            abort(403, 'Unauthorized');
        }
        $cases = Cases::where('user_id', Auth::user()->id)->where('status', 'closed')->get();
        $header_title = 'Closed Cases';
        return view('cases.index', compact('cases', 'header_title'));
    }
}
