<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPersediaanNon extends Model
{
    use HasFactory;

    protected $table = 'nilai_persediaan_non';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
