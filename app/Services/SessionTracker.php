<?php

namespace App\Services;

use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class SessionTracker
{
    protected IpGeolocation $ipGeolocation;

    public function __construct(IpGeolocation $ipGeolocation)
    {
        $this->ipGeolocation = $ipGeolocation;
    }

    /**
     * Start or update a session
     */
    public function track(): ?UserSession
    {
        if (!Auth::check()) {
            return null;
        }

        $sessionId = session()->getId();
        $userId = Auth::id();

        // Check if session already exists
        $userSession = UserSession::where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if ($userSession) {
            // Update last activity
            $userSession->last_activity_at = now();
            $userSession->save();
            return $userSession;
        }

        // Create new session
        return $this->startSession();
    }

    /**
     * Start a new session
     */
    public function startSession(): UserSession
    {
        $ip = Request::ip();
        $userAgent = Request::userAgent();
        $deviceInfo = $this->getDeviceInfo($userAgent);
        $location = $this->ipGeolocation->locate($ip);

        return UserSession::create([
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'platform' => $deviceInfo['platform'],
            'country' => $location['country'] ?? null,
            'region' => $location['region'] ?? null,
            'city' => $location['city'] ?? null,
            'latitude' => $location['latitude'] ?? null,
            'longitude' => $location['longitude'] ?? null,
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    /**
     * End a session
     */
    public function endSession(?string $sessionId = null): void
    {
        $sessionId = $sessionId ?? session()->getId();

        $userSession = UserSession::where('session_id', $sessionId)
            ->whereNull('ended_at')
            ->first();

        if ($userSession) {
            $userSession->ended_at = now();
            $userSession->calculateDuration();
        }
    }

    /**
     * End a specific user session by ID
     */
    public function endSessionById(int $sessionId): bool
    {
        $userSession = UserSession::find($sessionId);

        if ($userSession && $userSession->isActive()) {
            $userSession->ended_at = now();
            $userSession->calculateDuration();
            return true;
        }

        return false;
    }

    /**
     * Get device information from user agent
     */
    protected function getDeviceInfo(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'device_type' => 'unknown',
                'browser' => 'unknown',
                'platform' => 'unknown',
            ];
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        // Determine device type
        if ($agent->isMobile()) {
            $deviceType = 'mobile';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        } elseif ($agent->isDesktop()) {
            $deviceType = 'desktop';
        } else {
            $deviceType = 'unknown';
        }

        // Get browser name
        $browser = $agent->browser() ?: 'unknown';

        // Get platform/OS
        $platform = $agent->platform() ?: 'unknown';

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }
}
