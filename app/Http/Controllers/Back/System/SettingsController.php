<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Settings\Application\UpdateSetting;
use Thinktomorrow\Chief\Settings\SettingFieldsManager;

class SettingsController extends Controller
{
    /** @var SettingFieldsManager */
    private $settingFieldsManager;

    public function __construct(SettingFieldsManager $settingFieldsManager)
    {
        $this->settingFieldsManager = $settingFieldsManager;
    }

    public function edit()
    {
        $this->authorize('update-setting');

        return view('chief::back.system.settings', [
            'manager' => $this->settingFieldsManager,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('update-setting');

        $this->settingFieldsManager->fields()->validate($request->all());

        $this->settingFieldsManager->saveFields($request);

        return redirect()->route('chief.back.settings.edit')->with('messages.success', 'De settings zijn aangepast!');
    }
}
