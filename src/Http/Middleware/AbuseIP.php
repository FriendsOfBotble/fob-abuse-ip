<?php

namespace FriendsOfBotble\AbuseIP\Http\Middleware;

use Botble\Base\Facades\AdminHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AbuseIP
{
    protected array $whitelistedIPs = [];

    protected array $blacklistedIPs = [];

    public function __construct()
    {
        $whitelistIP = setting('fob_abuse_ip_whitelist_ips');

        if ($whitelistIP) {
            $this->whitelistedIPs = json_decode($whitelistIP, true) ?: [];
        }

        $blacklistIP = setting('fob_abuse_ip_blacklist_ips');

        if ($blacklistIP) {
            $this->blacklistedIPs = json_decode($blacklistIP, true) ?: [];
        }
    }

    public function handle(Request $request, Closure $next)
    {
        if (AdminHelper::isInAdmin(true) && auth()->check()) {
            return $next($request);
        }

        $ip = $request->ip();

        // Check if the IP is whitelisted
        if (in_array($ip, $this->whitelistedIPs)) {
            return $next($request); // Allow request if IP is whitelisted
        }

        if (in_array($ip, $this->blacklistedIPs) || $this->isInAbuseIP($ip)) {
            abort(403, 'Your IP address has been blocked');
        }

        return $next($request);
    }

    protected function isInAbuseIP(?string $ip): bool
    {
        if (is_string($ip)) {
            $ip = is_numeric($ip) ? (int) $ip : ip2long($ip);
        }

        return in_array($ip, $this->getIPs(), true);
    }

    protected function getIPs(): array
    {
        return Cache::get('abuse_ips', function () {
            $path = __DIR__ . '/../../../resources/data/abuse-ip.json';

            return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        });
    }
}
