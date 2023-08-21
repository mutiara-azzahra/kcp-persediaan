<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiReturDetails extends Model
{
    use HasFactory;

    protected $table = 'trns_retur_details';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function header_retur()
    {
        return $this->hasOne(TransaksiReturHeader::class, 'noretur', 'noretur');
    }
}
