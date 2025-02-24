<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('model_has_roles')
            ->where('model_type', 'Thinktomorrow\\Chief\\Admin\\Users\\User')
            ->update(['model_type' => 'chiefuser']);
    }

    public function down() {}
};
