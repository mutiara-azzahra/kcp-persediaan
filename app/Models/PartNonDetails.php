<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartNonDetails extends Model
{
    use HasFactory;

    protected $table = 'invoice_non_header';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function header(){

        return $this->belongsTo(PartNonHeader::class, 'invoice_non', 'invoice_non');

    }
}
