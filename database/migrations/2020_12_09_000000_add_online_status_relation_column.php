<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlineStatusRelationColumn extends Migration
{
    public function up()
    {
        Schema::table('relations', function (Blueprint $table) {
            $table->boolean('online_status')->default(1);
        });
    }

    public function down()
    {
        //
    }
}
