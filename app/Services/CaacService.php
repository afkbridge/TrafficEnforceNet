<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\LoginAttempt;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaacService
{
    /**
     * Get or create the device token for this browser/device.
     */
    public function getDeviceToken(Request $request): string
    {
        $token = $request->cookie('traffic_enforce_device');

        if (!$token) {
            $token = Str::random(64);
        }

        return $token;
    }

    /**
     * Register or update a trusted device.
     */
    public function registerDevice(
        User $user,
        Request $request,
        string $deviceToken
    ): TrustedDevice {
        return TrustedDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_token' => $deviceToken,
            ],
            [
                'device_name' => $this->getDeviceName($request),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_used_at' => now(),
                'is_trusted' => true,
            ]
        );
    }

    /**
     * Record a successful or failed CAAC login attempt.
     */
    public function recordLoginAttempt(
        ?User $user,
        Request $request,
        string $deviceToken,
        string $status,
        int $riskScore = 0,
        ?string $reason = null,
        ?float $latitude = null,
        ?float $longitude = null
    ): LoginAttempt {
        return LoginAttempt::create([
            'user_id' => $user?->id,
            'email' => $user?->email ?? $request->input('email'),
            'ip_address' => $request->ip(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'device_token' => $deviceToken,
            'user_agent' => $request->userAgent(),
            'status' => $status,
            'risk_score' => $riskScore,
            'reason' => $reason,
        ]);
    }

    /**
     * Record a CAAC access decision.
     */
    public function logAccess(
        ?User $user,
        Request $request,
        string $deviceToken,
        string $action,
        string $status,
        ?string $reason = null,
        ?float $latitude = null,
        ?float $longitude = null
    ): AccessLog {
        return AccessLog::create([
            'user_id' => $user?->id,
            'device_token' => $deviceToken,
            'ip_address' => $request->ip(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'user_agent' => $request->userAgent(),
            'action' => $action,
            'result' => $status,
            'reason' => $reason,
        ]);
    }

    /**
     * Get a simple device description.
     */
    private function getDeviceName(Request $request): string
    {
        $userAgent = $request->userAgent() ?? 'Unknown device';

        if (Str::contains($userAgent, ['Android'])) {
            return 'Android Device';
        }

        if (Str::contains($userAgent, ['iPhone', 'iPad'])) {
            return 'iOS Device';
        }

        if (Str::contains($userAgent, ['Windows'])) {
            return 'Windows PC';
        }

        if (Str::contains($userAgent, ['Macintosh'])) {
            return 'Mac';
        }

        if (Str::contains($userAgent, ['Linux'])) {
            return 'Linux Device';
        }

        return 'Unknown Device';
    }
}

