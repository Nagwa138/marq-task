<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_invoices' => Invoice::count(),
            'total_customers' => Customer::count(),
            'revenue' => Invoice::where('status', 'paid')->sum('total'),
            'pending_invoices' => Invoice::where('status', 'sent')->count(),
        ];

        $recent_invoices = Invoice::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_payments = Payment::with(['invoice', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recent_invoices', 'recent_payments'));
    }
}
