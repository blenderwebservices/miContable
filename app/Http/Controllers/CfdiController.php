<?php

namespace App\Http\Controllers;

use App\Models\Cfdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CfdiController extends Controller
{
    /**
     * Display a listing of CFDIs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->taxpayer) {
            abort(403, 'No taxpayer associated with this user.');
        }

        $rfc = $user->taxpayer->rfc;

        $query = Cfdi::forTaxpayer($rfc);

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('emission_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('emission_date', '<=', $request->to_date);
        }

        // Search by UUID or RFC
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                  ->orWhere('issuer_rfc', 'like', "%{$search}%")
                  ->orWhere('receiver_rfc', 'like', "%{$search}%");
            });
        }

        $cfdis = $query->latest('emission_date')->paginate(20);

        return view('cfdis.index', compact('cfdis'));
    }

    /**
     * Display the specified CFDI.
     */
    public function show(Cfdi $cfdi)
    {
        $user = Auth::user();
        
        if (!$user->taxpayer) {
            abort(403, 'No taxpayer associated with this user.');
        }

        $rfc = $user->taxpayer->rfc;

        // Check if user has access to this CFDI
        if ($cfdi->issuer_rfc != $rfc && $cfdi->receiver_rfc != $rfc) {
            abort(403, 'Unauthorized access to this CFDI.');
        }

        return view('cfdis.show', compact('cfdi'));
    }

    /**
     * Download CFDI XML or PDF.
     */
    public function download(Cfdi $cfdi, Request $request)
    {
        $user = Auth::user();
        
        if (!$user->taxpayer) {
            abort(403, 'No taxpayer associated with this user.');
        }

        $rfc = $user->taxpayer->rfc;

        // Check if user has access to this CFDI
        if ($cfdi->issuer_rfc != $rfc && $cfdi->receiver_rfc != $rfc) {
            abort(403, 'Unauthorized access to this CFDI.');
        }

        $type = $request->query('type', 'xml');

        if ($type === 'xml' && $cfdi->xml_path) {
            return Storage::disk('public')->download($cfdi->xml_path);
        } elseif ($type === 'pdf' && $cfdi->pdf_path) {
            return Storage::disk('public')->download($cfdi->pdf_path);
        }

        abort(404, 'File not found.');
    }
}
