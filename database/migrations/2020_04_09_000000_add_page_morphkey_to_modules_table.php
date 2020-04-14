<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Thinktomorrow\Chief\Modules\Module;

class AddPageMorphkeyToModulesTable extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            if (App::environment() != 'testing') {
                $table->dropForeign('modules_page_id_foreign');
            }

            $table->renameColumn('page_id', 'owner_id');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('owner_type')->nullable()->after('id');
            $table->index(["owner_type", "owner_id"], 'owner_index');
        });

        $this->migrateOwnerMorphKey();

        Schema::table('modules', function (Blueprint $table){
            $table->string('owner_type')->change();
        });
    }

    public function down()
    {
        //
    }

    private function migrateOwnerMorphKey()
    {
        $modules = Module::all();

        $modules->reject(function($module){
            return $module->owner_id == null;
        })->each(function($module){
            $module->owner_type = 'singles';
            $module->save();
        });
    }
}
