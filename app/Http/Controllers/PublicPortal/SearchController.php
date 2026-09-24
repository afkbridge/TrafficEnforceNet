<?php

namespace App\Http\Controllers\PublicPortal;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'ticket_number' => ['required', 'string', 'max:100'],
        ], [
            'ticket_number.required' => 'Please enter your ticket number.',
        ]);

        $ticketNumber = trim($request->ticket_number);

        // Get the ticket directly from the violations database table.
        $violation = Violation::with('violationType')
            ->where('ticket_number', $ticketNumber)
            ->first();

        // Ticket not found.
        if (!$violation) {
            return redirect('/#ticket-status')
                ->withInput()
                ->with(
                    'ticket_not_found',
                    'No traffic violation record was found for ticket number "' . $ticketNumber . '".'
                );
        }

        // Use the status stored in the database.
        // The database now allows only Pending and Settled.
        return redirect('/#ticket-status')
            ->with('ticket_result', $violation);
    }
}