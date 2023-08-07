<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartAOPMaster extends Model
{
    use HasFactory;

    protected $table = 'part_aop_master';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function part_no()
    {
        return $this->belongsTo(InitMasterPart::class, 'id_part_no', 'id');
    }

    public function modal()
    {
        return $this->belongsTo(PartAOPModal::class, 'id_part_no', 'id');
    }

}
