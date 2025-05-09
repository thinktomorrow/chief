<?php

namespace Thinktomorrow\Chief\Forms\Tests\Actions;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class RemovesFileDuplicatesWhenSavingFieldsTest extends FormsTestCase
{
    public function test_it_removes_duplicate_file_payload()
    {
        $file = UploadedFile::fake()->create('test.txt');

        $input = ['foo' => $file];
        $files = ['foo' => $file];

        // Access private method via Reflection
        $ref = new \ReflectionClass(SaveFields::class);
        $method = $ref->getMethod('removeDuplicateFilePayload');
        $method->setAccessible(true);

        [$newInput, $newFiles] = $method->invoke(new SaveFields, $input, $files);

        $this->assertArrayNotHasKey('foo', \Illuminate\Support\Arr::dot($newInput));
        $this->assertArrayHasKey('foo', \Illuminate\Support\Arr::dot($newFiles));
    }

    public function test_it_does_not_remove_non_matching_files()
    {
        $file = UploadedFile::fake()->create('test.txt');

        $input = ['foo' => $file];
        $files = ['foo2' => $file];

        $ref = new \ReflectionClass(SaveFields::class);
        $method = $ref->getMethod('removeDuplicateFilePayload');
        $method->setAccessible(true);

        [$newInput, $newFiles] = $method->invoke(new SaveFields, $input, $files);

        $this->assertArrayHasKey('foo', \Illuminate\Support\Arr::dot($newInput));
        $this->assertArrayHasKey('foo2', \Illuminate\Support\Arr::dot($newFiles));
    }
}
