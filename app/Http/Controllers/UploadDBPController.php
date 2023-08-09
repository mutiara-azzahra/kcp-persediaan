<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadDBPController extends Controller
{
    public function index()
    {
        return view('upload-dbp.index');
    }
}
