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

        $violation = Violation::with('violationType')
            ->where('ticket_number', $ticketNumber)
            ->first();

        if (!$violation) {
            return redirect('/')
                ->withInput()
                ->with(
                    'ticket_not_found',
                    'No traffic violation record was found for ticket number "' . $ticketNumber . '".'
                );
        }

        return redirect('/')
            ->with('ticket_result', $violation);
    }
}