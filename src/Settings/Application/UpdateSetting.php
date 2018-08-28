<?php

namespace Thinktomorrow\Chief\Settings\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Settings\Setting;

class UpdateSetting
{

    public function handle(array $data)
    {
        try {
            DB::beginTransaction();
            foreach($data as $key => $value){
                if($key && $value){
                    $setting = Setting::where('key', $key)->first();

                    if(!$setting) continue;

                    $setting->value = $value;
                    $setting->save();
                }
            }            

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
