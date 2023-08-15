<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){

        return view('user.index');

    }

    public function create(){

        return view('user.create');
        
    }

    public function store(){

        
        
    }
    public function registerStore(Request $request){

        $input['nama_user']         = $request->nama_kepala_keluarga;
        $input['password']          = Hash::make($request['password']);
        $input['id_role']           = 2;
        $user                       = User::create($input);

        return redirect('login')->with('success','Silahkan lanjutkan login!');
    }
}
