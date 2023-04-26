<?php

namespace Thinktomorrow\Chief\Tests\Unit\Media;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class InteractsWithMediaTest extends ChiefTestCase
{
    public function test_it_can_save_media_on_a_collection()
    {
        InteractsWithMediaStub::migrateUp();

        /** @var HasMedia $model */
        $model = InteractsWithMediaStub::create();
        $file = UploadedFile::fake()->create('media-one', 100);

        $model->addMedia($file)->toMediaCollection('images', 'local');

        $model->refresh();
        $this->assertCount(1, $model->getMedia('images'));
        $this->assertFalse( $model->hasMedia());
        $this->assertTrue( $model->hasMedia('images'));

            // public function media(): MorphMany;
            //
            //    public function addMedia(string|UploadedFile $file): FileAdder;
            //
            //    public function copyMedia(string|UploadedFile $file): FileAdder;
            //
            //    public function hasMedia(string $collectionName = ''): bool;
            //
            //    public function getMedia(string $collectionName = 'default', array|callable $filters = []): Collection;
            //
            //    public function clearMediaCollection(string $collectionName = 'default'): HasMedia;
            //
            //    public function clearMediaCollectionExcept(string $collectionName = 'default', array|Collection $excludedMedia = []): HasMedia;
            //
            //    public function shouldDeletePreservingMedia(): bool;
            //
            //    public function loadMedia(string $collectionName);
            //
            //    public function addMediaConversion(string $name): Conversion;
            //
            //    public function registerMediaConversions(Media $media = null): void;
            //
            //    public function registerMediaCollections(): void;
            //
            //    public function registerAllMediaConversions(): void;
    }

    public function test_it_can_save_localized_media_on_a_collection()
    {
        InteractsWithMediaStub::migrateUp();

        /** @var HasMedia $model */
        $model = InteractsWithMediaStub::create();
        $file = UploadedFile::fake()->create('media-one', 100);

        $model->addMedia($file)
            ->withCustomProperties(['locale' => 'fr'])
            ->toMediaCollection('images', 'local');

        $this->assertCount(0, $model->fresh()->getMedia('images', ['locale' => 'nl']));
        $this->assertCount(1, $model->fresh()->getMedia('images', ['locale' => 'fr']));
    }

    public function test_it_can_save_media_with_conversions()
    {
        InteractsWithMediaStub::migrateUp();

        /** @var HasMedia $model */
        $model = InteractsWithMediaStub::create();
        $file = UploadedFile::fake()->create('media-one', 100);

        $model->addMedia($file)->toMediaCollection('images', 'local');

        $model->refresh();
        $this->assertCount(1, $model->getMedia('images'));
        $this->assertFalse( $model->hasMedia());
        $this->assertTrue( $model->hasMedia('images'));
    }
}
