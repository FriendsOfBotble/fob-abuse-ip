<?php

namespace FriendsOfBotble\AbuseIP\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class AbuseIPSettingRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $whitelistIps = $this->parseTagInputToArray('fob_abuse_ip_whitelist_ips');

        if ($whitelistIps) {
            $this->merge([
                'fob_abuse_ip_whitelist_ips' => $whitelistIps,
            ]);
        }

        $blacklistIps = $this->parseTagInputToArray('fob_abuse_ip_blacklist_ips');

        if ($blacklistIps) {
            $this->merge([
                'fob_abuse_ip_blacklist_ips' => $blacklistIps,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'fob_abuse_ip_enabled' => [new OnOffRule()],
            'fob_abuse_ip_whitelist_ips' => ['nullable'],
            'fob_abuse_ip_whitelist_ips.*' => ['required', 'string'],
            'fob_abuse_ip_blacklist_ips' => ['nullable'],
            'fob_abuse_ip_blacklist_ips.*' => ['required', 'string'],
        ];
    }

    protected function parseTagInputToArray(string $name): array
    {
        $data = trim($this->input($name));

        if (! $data) {
            return [];
        }

        $data = collect(json_decode($data, true))
            ->map(function ($item) {
                return $item['value'];
            })
            ->all();

        if (! $data) {
            return [];
        }

        return $data;
    }

    public function attributes(): array
    {
        return [
            'fob_abuse_ip_whitelist_ips.*' => trans('plugins/fob-abuse-ip::abuse-ip.settings.whitelist_ips'),
            'fob_abuse_ip_blacklist_ips.*' => trans('plugins/fob-abuse-ip::abuse-ip.settings.blacklist_ips'),
        ];
    }
}
