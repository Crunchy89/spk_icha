<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $data = [
            'setting' => Setting::first()
        ];
        return view('login.index', $data);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $check = $request->only('username', 'password');
        if (Auth::attempt($check)) {
            return redirect()->route('dashboard')->with(['message' => 'Selamat Datang ' . ucfirst(Auth::user()->username)]);
        } else {
            return back()
                ->with(['message' => 'Username atau Password salah'])
                ->withInput(['username' => $request->username]);
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}
