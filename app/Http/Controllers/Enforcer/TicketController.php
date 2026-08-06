<?php

namespace App\Http\Controllers\Enforcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Issue Ticket Page
     */
    public function create()
    {
        return view('enforcer.issue-ticket');
    }

    /**
     * Submit Ticket
     */
    public function store(Request $request)
    {
        // OCR will be added later

        return redirect()
            ->route('enforcer.success')
            ->with('success', 'Ticket submitted successfully.');
    }

    /**
     * My Violations
     */
    public function index()
    {
        return view('enforcer.violations');
    }

    /**
     * Success Page
     */
    public function success()
    {
        return view('enforcer.success');
    }
}