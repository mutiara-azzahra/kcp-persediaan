<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelImport implements ToCollection
{
    protected $data = []; // Initialize an array to store the data

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Add each row to the data array
            $this->data[] = [
                'het'           => $row[0],
                'id_part_no'    => $row[1],
            ];
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
