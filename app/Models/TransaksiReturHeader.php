<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class TransaksiReturHeader extends Model
{
    use HasFactory;

    protected $table = 'trns_retur_header';
    protected $primaryKey = 'noretur';
    public $timestamps = false;

    public function details_retur()
    {
        return $this->hasOne(TransaksiReturDetails::class, 'noinv','noinv');
    }

}
