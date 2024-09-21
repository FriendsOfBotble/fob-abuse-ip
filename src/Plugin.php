<?php

namespace FriendsOfBotble\AbuseIP;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'fob_abuse_ip_whitelists',
        ]);
    }
}
