<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
    public function show()
    {
        return view('verify-code');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'access_code' => 'required',
        ]);

        if ($request->access_code === '123') {
            $request->session()->put('access_code_verified', true);
            return redirect('/login');
        }

        return back()->withErrors(['access_code' => 'Invalid code']);
    }
}
