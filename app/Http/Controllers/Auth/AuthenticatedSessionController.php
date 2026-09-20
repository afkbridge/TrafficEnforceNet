<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        // Record successful login
        AuditLogger::log(
            'LOGIN',
            'User logged in successfully.'
        );

        // Administrator
        if ($user->role->name === 'Administrator') {
            return redirect()->route('admin.dashboard');
        }

        // POSO Enforcer
        if ($user->role->name === 'POSO Enforcer') {
            return redirect()->route('enforcer.dashboard');
        }

        // BPLO Personnel
        if ($user->role->name === 'BPLO Personnel') {
            return redirect()->route('bplo.dashboard');
        }

        // Super Administrator
        if ($user->role->name === 'Super Administrator') {
            return redirect()->route('super-admin.users');
        }

        // Fallback
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Record logout before destroying the authenticated session
        AuditLogger::log(
            'LOGOUT',
            'User logged out.'
        );

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect every account type back to Office Login
        return redirect()->route('login');
    }
}