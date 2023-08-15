<?php

namespace App\Http\Controllers;

use App\Models\PartAOPDbp;
use Illuminate\Http\Request;

class DBPController extends Controller
{
    public function index()
    {

        $dbp = PartAOPDbp::all();

        return view('dbp.index', compact('dbp'));
    }

    public function upload(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file) {
            $import = new ExcelImport();

            Excel::import($import, $file);

            $data   = $import->getData();
            $now    = Carbon::now();

            foreach ($data as $row) {

            $inserted = PartAOPDbp::where('part_no', $row['part_no'])
                ->update([
                    'dbp'       => $row['dbp'],
                    'modi_date' => $now
                ]);
            }

            if ($inserted) {
                return redirect()->route('dbp.index')->with('success','Berhasil Upload');

            } else {
                return redirect()->route('dbp.index')->with('warning','Upload gagal');
            }
        }

        return redirect()->route('dbp.index')->with('danger','Upload gagal');
    }
}
