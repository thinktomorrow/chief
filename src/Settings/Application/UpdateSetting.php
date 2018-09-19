<?php

namespace Thinktomorrow\Chief\Settings\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Settings\Setting;

class UpdateSetting
{
    public function handle(array $data)
    {
        try {
            DB::beginTransaction();

            // Retrieve all current settings
            $settings = Setting::all();

            foreach($data as $key => $value){

                $setting = $settings->firstWhere('key', '=', $key);

                // If its the same, please do not bother updating.
                if($setting->value == $value) continue;

                $setting->update(['value' => $value]);
            }            

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
