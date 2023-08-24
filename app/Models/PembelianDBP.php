<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDBP extends Model
{
    use HasFactory;

    protected $table = 'invoice_aop_details_dbp';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
