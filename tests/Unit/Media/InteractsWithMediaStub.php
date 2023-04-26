<?php

namespace Thinktomorrow\Chief\Tests\Unit\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InteractsWithMediaStub extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'interacts_with_media_stubs';
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('interacts_with_media_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('old-picture')
            ->sepia()
            ->border(10, 'black', Manipulations::BORDER_OVERLAY);

        $this->addMediaConversion('thumb-cropped')
            ->crop('crop-center', 400, 400); // Trim or crop the image to the center for specified width and height.
    }
}
