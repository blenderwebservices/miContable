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

    /**
     * Get the users for the taxpayer.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the CFDIs where this taxpayer is the issuer.
     */
    public function issuedCfdis()
    {
        return $this->hasMany(Cfdi::class, 'issuer_rfc', 'rfc');
    }

    /**
     * Get the CFDIs where this taxpayer is the receiver.
     */
    public function receivedCfdis()
    {
        return $this->hasMany(Cfdi::class, 'receiver_rfc', 'rfc');
    }

    /**
     * Get all bank transactions for the taxpayer.
     */
    public function bankTransactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
