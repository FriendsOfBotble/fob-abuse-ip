<?php

namespace FriendsOfBotble\AbuseIP\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\AbuseIP\Http\Requests\Settings\AbuseIPSettingRequest;

class AbuseIPSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
            ]);

        $this
            ->setSectionTitle(trans('plugins/fob-abuse-ip::abuse-ip.settings.title'))
            ->setSectionDescription(trans('plugins/fob-abuse-ip::abuse-ip.settings.description'))
            ->setValidatorClass(AbuseIPSettingRequest::class)
            ->add(
                'fob_abuse_ip_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-abuse-ip::abuse-ip.settings.enable_abuse_ip'))
                    ->defaultValue($targetValue = ((bool) setting('fob_abuse_ip_enabled', true)))
                    ->helperText(trans('plugins/fob-abuse-ip::abuse-ip.settings.enable_abuse_ip_helper'))
            )
            ->addOpenCollapsible('fob_abuse_ip_enabled', '1', $targetValue)
            ->add(
                'fob_abuse_ip_whitelist_ips',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->addAttribute('class', 'tags form-control')
                    ->addAttribute('data-counter', '250')
                    ->value(setting('fob_abuse_ip_whitelist_ips'))
                    ->label(trans('plugins/fob-abuse-ip::abuse-ip.settings.whitelist_ips'))
                    ->placeholder(trans('plugins/fob-abuse-ip::abuse-ip.settings.whitelist_ips_placeholder'))
                    ->helperText(trans('plugins/fob-abuse-ip::abuse-ip.settings.whitelist_ips_helper'))
            )
            ->add(
                'fob_abuse_ip_blacklist_ips',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->addAttribute('class', 'tags form-control')
                    ->addAttribute('data-counter', '250')
                    ->value(setting('fob_abuse_ip_blacklist_ips'))
                    ->label(trans('plugins/fob-abuse-ip::abuse-ip.settings.blacklist_ips'))
                    ->placeholder(trans('plugins/fob-abuse-ip::abuse-ip.settings.blacklist_ips_placeholder'))
                    ->helperText(trans('plugins/fob-abuse-ip::abuse-ip.settings.blacklist_ips_helper'))
            )
            ->addCloseCollapsible('fob_abuse_ip_enabled', '1');
    }
}
