<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Settings\Application\UpdateSetting;
use Thinktomorrow\Chief\Settings\SettingFields;

class SettingsController extends Controller
{
    public function edit()
    {
        $this->authorize('update-setting');

        $fields = SettingFields::defaults();

        return view('chief::back.system.settings', [
            'fields' => $fields
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('update-setting');

        app(UpdateSetting::class)->handle(
            $request->get('settings')
        );

        return redirect()->route('chief.back.settings.edit')->with('messages.success', 'Settings zijn aangepast!');
    }
}
