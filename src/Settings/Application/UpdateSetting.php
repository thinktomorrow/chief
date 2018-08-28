<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Models\UniqueSlug;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class UpdateSetting
{

    public function handle($id, array $data)
    {
        try {
            DB::beginTransaction();

            foreach($data as $key => $value){
                Setting::create([
                    'key'   => $key,
                    'value' => $value,
                    'field' => [
                        'type'        => FieldType::HTML,
                        'label'       => 'homepage',
                        'description' => 'extra information',
                    ],
                ]);
            }            

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
