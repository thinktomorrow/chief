
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeModuleSlugToInternalTitle extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('slug', 'internal_title');
        });
    }

    public function down()
    {
    }
}
