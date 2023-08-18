<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartNonHeader extends Model
{
    use HasFactory;

    protected $table = 'invoice_non_header';
    protected $primaryKey = 'invoice_non';
    public $timestamps = false;

    public function details(){

        return $this->hasMany(PartNonDetails::class, 'invoice_non', 'invoice_non');

    }

}
