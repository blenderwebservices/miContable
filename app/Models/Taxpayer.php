<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxpayer extends Model
{
    protected $fillable = [
        'rfc',
        'name',
        'tax_regime',
        'fiel_path',
        'ciec_encrypted',
    ];
}
