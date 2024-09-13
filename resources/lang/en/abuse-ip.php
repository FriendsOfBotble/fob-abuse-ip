<?php

return [
    'settings' => [
        'title' => 'Abuse IP',
        'description' => 'Manage Whitelist/Blacklist Known Spam IPs.',
        'enable_abuse_ip' => 'Enable Abuse IP',
        'enable_abuse_ip_helper' => 'Spam IPs is loaded from https://raw.githubusercontent.com/rahulalam31/Laravel-Abuse-IP/main/abuseip.json',
        'whitelist_ips' => 'Whitelist IPs',
        'whitelist_ips_placeholder' => 'Add IPs to whitelist',
        'whitelist_ips_helper' => 'Whitelist IPs will be ignored by the Abuse IP feature. Separate IPs by commas.',
        'blacklist_ips' => 'Blacklist IPs',
        'blacklist_ips_placeholder' => 'Add IPs to blacklist',
        'blacklist_ips_helper' => 'Blacklist IPs will be blocked by the Abuse IP feature. Separate IPs by commas.',

    ],
];
