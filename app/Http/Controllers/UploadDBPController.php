<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartAOPMaster;
use App\Models\PartAOPModal;

class UploadDBPController extends Controller
{
    public function index()
    {

        $dbp = PartAOPMaster::all();

        $test = PartAOPModal::all();

        return view('upload-dbp.index', compact('dbp', 'test'));
    }
}
