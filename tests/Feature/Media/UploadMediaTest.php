<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Media;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\FormParams;
use Thinktomorrow\Chief\Tests\TestCase;

class UploadMediaTest extends TestCase
{
    use FormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    function a_new_asset_can_be_uploaded()
    {
        $page = Page::create(['collection' => 'statics']);

        // Upload asset
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validPageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));

        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

    /** @test */
    function a_new_asset_can_be_uploaded_as_regular_file()
    {
        $this->markTestIncomplete();

        $page = Page::create(['collection' => 'statics']);

        // Upload asset

        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

    /** @test */
    function an_asset_can_be_replaced()
    {
        $this->disableExceptionHandling();

        $page = Page::create(['collection' => 'statics']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $existing_asset = $page->getAllFiles(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validPageParams([
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $existing_asset->id => $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $page->fresh()->getAllFiles(MediaType::HERO));
        $this->assertContains('tt-favicon.png', $page->fresh()->getFileUrl(MediaType::HERO));
    }

    /** @test */
    function an_asset_can_be_removed()
    {
        $page = Page::create(['collection' => 'statics']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));

        // Remove asset
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validPageParams([
                'files' => [
                    MediaType::HERO => [
                        'remove' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id,
                        ]
                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertFalse($page->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(0, $page->fresh()->getAllFiles());
    }

    /** @test */
    function it_can_upload_image_with_uppercased_extension()
    {
        // Currently uploaded a xxx.JPEG fails retrieval as the source by Slim
        $this->markTestIncomplete();
    }

    private function dummySlimImagePayload()
    {
        return '{"server":null,"meta":{},"input":{"name":"tt-favicon.png","type":"image/png","size":5558,"width":32,"height":32,"field":null},"output":{"name":"tt-favicon.png","type":"image/png","width":32,"height":32,"image":"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAEp0lEQVRYR+1XTW8bVRQ94/HY46/4K4lTF1KipBASEbVpELQpICRAYlFgQRelCxZ8SLAoDRIFpC5Dmq5AINT+ASIEAqGABCskBIiEqiQNoVDR0CihiRvHju3Escfzhe6zY894nDhBrbrpkyxr5s1798w95553h9N1XcdtHNwdADvJQIUsHbIGyFqRPcFGPw4A/QCu+LetsS0KKHBB0zCVlHBxOYeZTAEJSYGkFgE4eA4hJ482nwO9YRf2h0WIvG1bQLYEQNsXVA3f/buGb+dXkZRUcHVejzTd4ODx5G4vjrT64KoDZFMAFHwmI+Hjy0lcz8p1A1vzraNRtOP1+0PoCjg3XV8TAAW/EF/Hh38kyzxvi9AaD/Ec8GpnCI+1uGuCsAAgvqdX8jhzKQ5F35DV/w1fXEeafKM7jIcj7pJMK/tZAKxIKt76NYZVknlpEK/3+BwIOHl2559MwTTfLPLY5RHY3GJWxlJetSAWeQ7DD0bQ4hZMIEwAKNC5P5P4IZYtlxTtpEPHya4wDkY87GpoMo6pFakc5NlWH461+9n1yNU0RudXa6asNyziVE+jiQoTgHhOwcmxRZb6ytsXAQx0h3GoxQOaem9iiZXkxnhujw/HOgLs8pO/UxidqwAwFg1RQVmgbG6MMgDifnQug5GZtAl9u0/AS/cGEXHZ4XXwDEAsKyOnVCjyO3mERDtbl8wrSEsq84jBko6MG1JpHm8PlD3CBODsVBwTibwJAPH7SMSDwy1uRD0CA/DjQhaxnFJ+rjvoRHdIZHNTiRyupApQNB3fzK+iWg0dDQ4MHmgu02AAoGNgLIZFw8ZGEb7T04TeJhcLMkQUGDRwtK0Bz7f52dwIUbCJBmg/v2DDuf4oeGbdQBmAput47ecFpAqV1G4AoLl3bxIAj53D+f7dzL4tACgDxtTeCgA+wYbz/VHYqzNAIhy6ZFb3rQDQ5hNwpi9SSwPAF7NpfH6NqsB8nlZTMDwZx2SyIlajBj69msJXhjKsNoSn7/Lixb1BaxUwF1uX8eZ4DKVj3uAFOk50hXF4lweUqa/nMvjsWhp2jkNO1XHkbh+OdxRL67flHD6YToAynFfJQcyDKmCv32n1AbpDTvj+dALj8ZzFyR4IOvF2T1OJOx05Rcf1NRmnJ5bQJPIY7ovAI5BV6yxwVtZw6sINZA1+QeV6el8TbAZ3qrJi4KcbWXx0OQGuigYC1+oV0BkQ2ZvG1xVMp/KQ1aJThp127G8UWXllJBW/r+SxJmtlrqlrGjwQwR6vw9SoWM6Cs1PLFjOypGMHN4gCDjpevi+EJ6JeS5dkArCcV3Dil0WUOq0dhNn60Rfa/Xim1bd1P0DiqlQBwHMc+hpduJKWkCqQoe6g0yzh8dpteKUziIearX2A5TAi7x4Yj7Ee8PGoF09FPQg6eawpGr6czeD7hSzyJKi6La8OwWbDoy1uHG3zsx5iK+hlCmLrCv5KSzjY7ILDZu5oSYDUoIzFc6zMZlcLSMtauVyp5MjhSGD7wiIONbsRcNAe9bNmOg2ZN2+xpvhdoDON5FUN64rOfMFt5+Cy21C0d65+kgyS2dZ3wU1TY42N7gC47Rn4Dw+ni78hQfokAAAAAElFTkSuQmCC"},"actions":{"rotation":null,"crop":{"x":0,"y":0,"height":32,"width":32,"type":"auto"},"size":null,"filters":{"sharpen":0},"minSize":{"width":0,"height":0}}}';
    }
}
