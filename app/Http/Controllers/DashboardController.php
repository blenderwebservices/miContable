<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cfdi;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->taxpayer) {
            abort(403, 'No taxpayer associated with this user.');
        }

        $taxpayer = $user->taxpayer;
        $rfc = $taxpayer->rfc;

        // Get financial summary
        $summary = $this->getFinancialSummary($rfc);
        
        // Get recent CFDIs
        $recentCfdis = Cfdi::forTaxpayer($rfc)
            ->latest('emission_date')
            ->take(10)
            ->get();

        // Get recent transactions
        $recentTransactions = BankTransaction::latest('date')
            ->take(10)
            ->get();

        // Get monthly data for charts
        $monthlyData = $this->getMonthlyData($rfc);

        return view('dashboard', compact(
            'taxpayer',
            'summary',
            'recentCfdis',
            'recentTransactions',
            'monthlyData'
        ));
    }

    /**
     * Calculate financial summary.
     */
    private function getFinancialSummary($rfc)
    {
        $currentYear = now()->year;

        // Income (issued invoices)
        $totalIncome = Cfdi::where('issuer_rfc', $rfc)
            ->income()
            ->whereYear('emission_date', $currentYear)
            ->sum('total');

        // Expenses (received invoices)
        $totalExpenses = Cfdi::where('receiver_rfc', $rfc)
            ->expense()
            ->whereYear('emission_date', $currentYear)
            ->sum('total');

        // Taxes
        $totalTaxes = Cfdi::where('issuer_rfc', $rfc)
            ->whereYear('emission_date', $currentYear)
            ->sum('tax_amount');

        // Balance
        $balance = $totalIncome - $totalExpenses;

        return [
            'income' => $totalIncome,
            'expenses' => $totalExpenses,
            'taxes' => $totalTaxes,
            'balance' => $balance,
        ];
    }

    /**
     * Get monthly data for charts.
     */
    private function getMonthlyData($rfc)
    {
        $currentYear = now()->year;
        $months = [];
        $income = [];
        $expenses = [];

        for ($month = 1; $month <= 12; $month++) {
            $months[] = date('M', mktime(0, 0, 0, $month, 1));

            // Monthly income
            $monthlyIncome = Cfdi::where('issuer_rfc', $rfc)
                ->income()
                ->whereYear('emission_date', $currentYear)
                ->whereMonth('emission_date', $month)
                ->sum('total');
            $income[] = $monthlyIncome;

            // Monthly expenses
            $monthlyExpenses = Cfdi::where('receiver_rfc', $rfc)
                ->expense()
                ->whereYear('emission_date', $currentYear)
                ->whereMonth('emission_date', $month)
                ->sum('total');
            $expenses[] = $monthlyExpenses;
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }
}
