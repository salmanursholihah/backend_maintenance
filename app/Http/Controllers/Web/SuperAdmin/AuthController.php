<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        public function login()
    {
        return view('pages.auth.login');
    }

public function loginSubmit(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->remember)) {

        $request->session()->regenerate();

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return back()->with('error', 'Akses hanya untuk super admin');
        }

        return redirect('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
}

public function register()
    {
        return view('pages.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|confirmed|min:6',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'admin',
            'is_active'=>true,
        ]);

        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('pages.forgot_password');
    }

    public function forgotPasswordSubmit(Request $request)
    {
        return back()->with('success','Reset password link dikirim');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}





