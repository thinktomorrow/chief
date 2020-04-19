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

        Schema::table('modules', function (Blueprint $table) {
            $table->string('owner_type')->change();
        });
    }

    public function down()
    {
        //
    }

    private function migrateOwnerMorphKey()
    {
        $moduleRecords = \Illuminate\Support\Facades\DB::table('modules')->get();

        foreach($moduleRecords as $moduleRecord) {

            if(!$moduleRecord->owner_id) continue;

            try{
                $page = \Thinktomorrow\Chief\Pages\Page::find($moduleRecord->owner_id);

                if(!$page) {
                    throw new Exception('No page found by id ' . $moduleRecord->owner_id);
                }

                \Illuminate\Support\Facades\DB::table('modules')->where('id', $moduleRecord->id)->update([
                    'owner_type' => $page->getMorphClass(),
                ]);
            } catch(Exception $e)
            {
                echo "Could not determine page owner_type for page id [$moduleRecord->owner_id], module morph_key: $moduleRecord->morph_key, error: ".$e->getMessage()." \n";
            }
        }


    }
}
