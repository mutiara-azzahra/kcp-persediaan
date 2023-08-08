<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLevel4 extends Model
{
    use HasFactory;

    protected $table = 'mst_level4';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function master(){
        return $this->hasMany(PartAOPMaster::class, 'id', 'id');
    }



    
}
