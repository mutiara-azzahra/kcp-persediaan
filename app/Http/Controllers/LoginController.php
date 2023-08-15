<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function formLogin(){

        return view('login');
    }

    public function login(Request $request){

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            
            return redirect()->route('dashboard');
        }

        return back()->with('danger','Username atau password salah!');
    }

    public function formRegister(){

        return view('register');

    }

    public function register(Request $request){

            $request -> validate([
                'nama_user'     => 'required',
                'username'      => 'required',
            ]);
    
            $input = $request->all();
    
            $input['nama_user']         = $request->nama_user;
            $input['username']          = $request->username;
            $input['password']          = Hash::make($request['password']);

            $user                       = User::create($input);
    
            return redirect('login')->with('success','Silahkan lanjutkan login!');

    }


}
