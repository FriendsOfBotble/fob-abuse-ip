<?php

namespace FriendsOfBotble\AbuseIp\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use FriendsOfBotble\AbuseIp\Forms\Settings\AbuseIPSettingForm;
use Botble\Setting\Http\Controllers\SettingController;
use FriendsOfBotble\AbuseIp\Http\Requests\Settings\AbuseIPSettingRequest;

class AbuseIPSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/fob-abuse-ip::abuse-ip.settings.title'));

        return AbuseIPSettingForm::create()->renderForm();
    }

    public function update(AbuseIPSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
