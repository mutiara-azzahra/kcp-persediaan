<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartAOPModal;

class UploadDBPController extends Controller
{
    public function index()
    {

        $dbp = PartAOPModal::all();

        return view('upload-dbp.index', compact('dbp'));
    }
}
