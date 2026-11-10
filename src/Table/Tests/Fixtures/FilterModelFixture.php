<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterModelFixture extends Model
{
    public $table = 'chief_table_filter_model_fixtures';

    public $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public static function migrateUp(): void
    {
        Schema::create('chief_table_filter_model_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('category')->nullable();
            $table->boolean('is_featured')->default(false);
        });
    }
}
