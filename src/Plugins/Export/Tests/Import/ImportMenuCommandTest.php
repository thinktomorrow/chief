<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;

class ImportMenuCommandTest extends TestCase
{
    public function test_it_can_import_menu()
    {
        $menu = Menu::create(['type' => 'main']);

        MenuItem::create([
            'menu_id' => $menu->id,
            'values' => json_encode([
                'url' => ['nl' => '/nl-link', 'en' => '/en-link'],
                'label' => ['nl' => 'test nl', 'en' => 'test en'],
                'owner_label' => ['nl' => 'test owner nl', 'en' => 'test owner en'],
            ]),
        ]);

        $this->artisan('chief:export-menu');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-menu-'.date('Y-m-d').'.xlsx');

        // Change the database text
        MenuItem::find(1)->update([
            'values' => json_encode([
                'url' => ['nl' => '/nl-link', 'en' => '/en-link'],
                'label' => ['nl' => 'changed nl', 'en' => 'changed en'],
                'owner_label' => ['nl' => 'changed owner nl', 'en' => 'changed owner en'],
            ]),
        ]);

        // Now import it again
        $this->artisan('chief:import-menu', ['file' => $filepath]);

        $this->assertEquals('test nl', MenuItem::find(1)->dynamic('label', 'nl'));
        $this->assertEquals('test en', MenuItem::find(1)->dynamic('label', 'en'));

        // owner label is not imported
        $this->assertEquals('changed owner nl', MenuItem::find(1)->dynamic('owner_label', 'nl'));
        $this->assertEquals('changed owner en', MenuItem::find(1)->dynamic('owner_label', 'en'));
    }
}
