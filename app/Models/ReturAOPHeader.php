<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturAOPHeader extends Model
{
    use HasFactory;

    protected $table = 'retur_aop_header';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
