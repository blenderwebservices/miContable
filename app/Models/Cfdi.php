<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cfdi extends Model
{
    protected $fillable = [
        'uuid',
        'emission_date',
        'issuer_rfc',
        'receiver_rfc',
        'total',
        'tax_amount',
        'xml_path',
        'pdf_path',
        'type',
        'payment_method',
        'payment_form',
        'currency',
    ];

    protected $casts = [
        'emission_date' => 'datetime',
        'total' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    /**
     * Create a CFDI record from parsed XML data
     *
     * @param array $data Parsed XML data
     * @param string $xmlPath Path to the stored XML file
     * @return self
     */
    public static function createFromXml(array $data, string $xmlPath): self
    {
        $data['xml_path'] = $xmlPath;
        
        return self::create($data);
    }

    /**
     * Scope a query to only include income CFDIs.
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'Ingreso');
    }

    /**
     * Scope a query to only include expense CFDIs.
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'Egreso');
    }

    /**
     * Scope a query to filter CFDIs for a specific taxpayer RFC.
     */
    public function scopeForTaxpayer($query, $rfc)
    {
        return $query->where(function ($q) use ($rfc) {
            $q->where('issuer_rfc', $rfc)
              ->orWhere('receiver_rfc', $rfc);
        });
    }
}
