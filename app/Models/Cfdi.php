<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cfdi extends Model
{
    protected $fillable = [
        'uuid',
        'issuer_rfc',
        'receiver_rfc',
        'total',
        'tax_amount',
        'xml_path',
        'pdf_path',
        'type',
    ];
}
