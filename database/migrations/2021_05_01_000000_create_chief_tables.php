<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiefTables extends Migration
{
    public function up()
    {
        Schema::create('chief_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->boolean('enabled')->default(0);
            $table->rememberToken();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });

        Schema::create('chief_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });

        $this->createPermissionTables();

        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invitee_id');
            $table->unsignedInteger('inviter_id');
            $table->string('state')->default('none');
            $table->string('token')->unique();
            $table->dateTime('expires_at');
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('type', ['internal', 'custom', 'nolink'])->default('custom');
            $table->string('menu_type')->default('main');
            $table->boolean('hidden_in_menu')->default(false);
            $table->string('owner_type')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->json('values')->nullable();
            $table->integer('order')->default(0);
        });

        Schema::create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->integer('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();

            $table->index('log_name');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->text('value');
        });

        Schema::create('chief_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('redirect_id')->nullable();
            $table->string('locale');
            $table->string('slug');
            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->foreign('redirect_id')->references('id')->on('chief_urls')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chief_users');
        Schema::dropIfExists('chief_password_resets');

        $permissionTableNames = config('permission.table_names');
        Schema::dropIfExists($permissionTableNames['role_has_permissions']);
        Schema::dropIfExists($permissionTableNames['model_has_roles']);
        Schema::dropIfExists($permissionTableNames['model_has_permissions']);
        Schema::dropIfExists($permissionTableNames['roles']);
        Schema::dropIfExists($permissionTableNames['permissions']);

        Schema::dropIfExists('invitations');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_item_translations');

        Schema::dropIfExists(config('activitylog.table_name'));
        Schema::dropIfExists('settings');
        Schema::dropIfExists('chief_urls');
    }

    private function createPermissionTables()
    {
        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('role_id');
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')->forget('spatie.permission.cache');
        });
    }
}
