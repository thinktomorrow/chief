<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Squanto\Database\Application\AddDatabaseLine;
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\LineKey;
use Thinktomorrow\Squanto\Domain\Metadata\Metadata;

class ImportTextCommandTest extends TestCase
{
    public function test_it_can_import_squanto_text()
    {
        // First export the text
        app(AddDatabaseLine::class)->handle(
            $line = Line::fromRaw('about.title', ['nl' => 'test nl', 'en' => 'test en']),
            Metadata::fromLine($line)
        );

        $this->artisan('chief:export-text');
        $filepath = storage_path('app/exports/'.date('Ymd-His').'/'.config('app.name') .'-text-'.date('Y-m-d').'.xlsx');

        // Change the database text
        $line = DatabaseLine::findByKey(LineKey::fromString('about.title'));
        $line->setDynamic('value.nl', 'changed nl');
        $line->setDynamic('value.en', 'changed en');
        $line->save();

        // Now import it again
        $this->artisan('chief:import-text', ['file' => $filepath])
            ->expectsQuestion("Which column contains the ID references? Choose one of: id, groep, label, nl, en, opmerking", 'id')
            ->expectsQuestion("Which column would you like to import? Choose one of: id, groep, label, nl, en, opmerking", 'nl')
            ->expectsQuestion("Which locale does this column represent? Choose one of: nl, en", 'nl');

        $this->assertEquals('test nl', DatabaseLine::findByKey(LineKey::fromString('about.title'))->dynamic('value', 'nl'));
        $this->assertEquals('changed en', DatabaseLine::findByKey(LineKey::fromString('about.title'))->dynamic('value', 'en'));
    }
}
