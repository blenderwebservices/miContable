<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = [
        'date',
        'description',
        'amount',
        'reference',
        'bank_account_id',
    ];
}
