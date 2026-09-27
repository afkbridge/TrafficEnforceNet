<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TrustedDevice;
use App\Services\AuditLogger;
use App\Services\CaacService;
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
    public function store(
        LoginRequest $request,
        CaacService $caacService
    ) {
        /*
         * Get the device token before authentication.
         *
         * If this browser already has a CAAC device cookie,
         * the same token will be used. Otherwise, a new token
         * will be generated.
         */
        $deviceToken = $caacService->getDeviceToken($request);

        // Authenticate the user using the existing Laravel login process.
        $request->authenticate();

        // Regenerate the session after successful authentication.
        $request->session()->regenerate();

        $user = auth()->user();

        /*
         * Get the location captured by the browser.
         *
         * These values are optional because the user may deny
         * browser location permission or the browser may not
         * provide a location.
         */
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        /*
         * Check whether this device is already trusted
         * for the authenticated user.
         */
        $trustedDevice = TrustedDevice::where('user_id', $user->id)
            ->where('device_token', $deviceToken)
            ->where('is_trusted', true)
            ->first();

        if ($trustedDevice) {
            // Existing trusted device.
            $riskScore = 0;
            $reason = 'Trusted device';

            // Update information about the device's latest use.
            $trustedDevice->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_used_at' => now(),
            ]);
        } else {
            // New device for this user.
            $riskScore = 10;
            $reason = 'New device registered';

            // Register this device as trusted.
            $caacService->registerDevice(
                $user,
                $request,
                $deviceToken
            );
        }

        /*
         * Record the CAAC login attempt.
         */
        $caacService->recordLoginAttempt(
            $user,
            $request,
            $deviceToken,
            'success',
            $riskScore,
            $reason,
            $latitude,
            $longitude
        );

        /*
         * Record the CAAC access decision.
         */
        $caacService->logAccess(
            $user,
            $request,
            $deviceToken,
            'login',
            'allowed',
            $reason,
            $latitude,
            $longitude
        );

        // Record successful login in the existing audit trail.
        AuditLogger::log(
            'LOGIN',
            'User logged in successfully.'
        );

        // Administrator
        if ($user->role->name === 'Administrator') {
            $response = redirect()->route('admin.dashboard');
        }

        // POSO Enforcer
        elseif ($user->role->name === 'POSO Enforcer') {
            $response = redirect()->route('enforcer.dashboard');
        }

        // BPLO Personnel
        elseif ($user->role->name === 'BPLO Personnel') {
            $response = redirect()->route('bplo.dashboard');
        }

        // Super Administrator
        elseif ($user->role->name === 'Super Administrator') {
            $response = redirect()->route('super-admin.users');
        }

        // Fallback
        else {
            $response = redirect('/');
        }

        /*
         * Store the CAAC device token in the browser.
         *
         * 1 year = 525600 minutes.
         *
         * HttpOnly prevents JavaScript from reading the token.
         * SameSite=Lax is suitable for the current login flow.
         */
        return $response->withCookie(
            cookie(
                'traffic_enforce_device',
                $deviceToken,
                60 * 24 * 365,
                '/',
                null,
                false,
                true,
                false,
                'lax'
            )
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Record logout before destroying the authenticated session.
        AuditLogger::log(
            'LOGOUT',
            'User logged out.'
        );

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect every account type back to Office Login.
        return redirect()->route('login');
    }
}