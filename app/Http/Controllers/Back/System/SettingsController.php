<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Settings\SettingFieldsManager;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;

class SettingsController extends Controller
{
    /** @var FieldManager */
    private $settingFieldsManager;

    /** @var FieldValidator */
    private $fieldValidator;

    public function __construct(SettingFieldsManager $settingFieldsManager, FieldValidator $fieldValidator)
    {
        $this->settingFieldsManager = $settingFieldsManager;
        $this->fieldValidator = $fieldValidator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $this->authorize('update-setting');

        return view('chief::admin.settings', [
            'manager' => $this->settingFieldsManager,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('update-setting');

        $this->fieldValidator->handle($this->settingFieldsManager->fields(), $request->all());

        $this->settingFieldsManager->saveEditFields($request);

        return redirect()->route('chief.back.settings.edit')->with('messages.success', 'De settings zijn aangepast!');
    }
}
