<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function register(Request $request) {

    }

    public function loginForm()
    {
        try{
            return view('auth.login');

        }catch(\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with(["error" => "Credentials don't match."]);
        }
        $user->load(['tenant', 'role', 'shortUrls']);
        $request->session()->regenerate();
        return redirect()->route('shortUrlList');
        // return view('short_url.index', compact(['user']));

    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
