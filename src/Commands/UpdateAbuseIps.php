<?php

namespace FriendsOfBotble\AbuseIP\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UpdateAbuseIps extends Command
{
    protected $signature = 'fob:abuse-ip:update';

    protected $description = 'update the abuse IP list.';

    public function handle(): void
    {
        $this->info('Fetching IP blockList...');

        // fetch the IP blocklist
        $ips = $this->fetchIpsFromSources(['https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-14d.ipv4']);

        if (empty($ips)) {
            $this->error('Failed to fetch IP blocklist');

            return;
        }

        $ips = array_map(fn (string $ip) => ip2long($ip), $ips);

        file_put_contents(
            __DIR__ . '/../../resources/data/abuse-ip.json',
            json_encode($ips)
        );

        try {
            Cache::forever('abuse_ips', $ips);

            $this->info('IP blocklist updated successfully');
        } catch (QueryException) {
            Cache::forget('abuse_ips');

            $this->warn('IP blocklist saved to file, but is too long to cache in database');
        }
    }

    protected function fetchIpsFromSources(array $sources): array
    {
        $ips = [];
        foreach ($sources as $source) {
            $response = Http::withoutVerifying()->timeout(300)->get($source);
            if ($response->successful()) {
                $sourceIps = $this->parseBlocklist($response->body());
                $ips = array_merge($ips, $sourceIps);
            } else {
                $this->error("Failed to fetch from source: $source");
            }
        }

        return array_values(array_unique($ips));
    }

    protected function parseBlocklist(string $blocklist): array
    {
        $lines = explode("\n", $blocklist);

        // Remove inline comments and validate that every line contains a valid IP address
        return array_filter(
            array_map(fn ($line) => preg_replace('/\s*#.*$/', '', trim($line)), $lines),
            fn ($line) => filter_var($line, FILTER_VALIDATE_IP) !== false
        );
    }
}
