<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('chief::back.system.settings');
    }

    public function store()
    {
        $this->authorize('update-setting');

        $module = app(UpdateSetting::class)->handle(
            $request->get('settings')
        );
        
        return redirect()->route('chief.back.system.edit')->with('messages.success', 'Settings aangepast!');
    }

    public function update()
    {
        
    }
}
