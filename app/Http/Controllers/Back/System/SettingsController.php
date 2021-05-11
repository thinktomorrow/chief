<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Settings\SettingFields;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;

class SettingsController extends Controller
{
    private SettingFields $settingFields;
    private FieldValidator $fieldValidator;

    public function __construct(SettingFields $settingFields, FieldValidator $fieldValidator)
    {
        $this->settingFields = $settingFields;
        $this->fieldValidator = $fieldValidator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $this->authorize('update-setting');

        return view('chief::admin.settings', [
            'fields' => $this->settingFields->populatedFields(),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('update-setting');

        $fields = $this->settingFields->populatedFields();

        $this->fieldValidator->handle($fields, $request->all());

        $this->settingFields->saveFields($fields, $request->all(), $request->allFiles());

        return redirect()->route('chief.back.settings.edit')->with('messages.success', 'De settings zijn aangepast!');
    }
}
