<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.signin');
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended('/apps/admin/dashboard')
                ->with('success', 'Welcome ' . $user->name);
        }

        return back()
            ->with('error', 'Incorrect email or password.')
            ->onlyInput('email');
    }
}
