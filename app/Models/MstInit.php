<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstInit extends Model
{
    use HasFactory;

    protected $table = 'mst_init';
    protected $primaryKey = 'id';
    public $timestamps = false;
}


