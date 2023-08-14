<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartAOPDbp extends Model
{
    use HasFactory;

    protected $table = 'part_aop_dbp';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function dbp()
    {
        return $this->hasMany(TransaksiInvoiceDetails::class, 'part_no', 'part_no');
    }

    public function details()
    {
        return $this->hasMany(InvoiceAOPDetails::class, 'part_no', 'part_no');
    }
}
