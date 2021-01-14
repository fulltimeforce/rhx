<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    function index()
    {
        return view('emails.mail');
    }

    function send(Request $request)
    {
        $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required'
        ]);

        $data = array(
            'name' => $request->name,
            'message' => $request->message
        );

        Mail::to('Receiver Email Address')->send(new sendingEmail($data));
        return back()->with('success', 'Thanks for contacting us!');
    }
}
