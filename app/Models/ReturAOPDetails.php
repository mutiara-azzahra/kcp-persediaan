<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturAOPDetails extends Model
{
    use HasFactory;

    protected $table = 'retur_aop_details';
    protected $primaryKey = 'id';
    public $timestamps = false;

    
}
