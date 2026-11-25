<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\CheckboxFilter;
use Thinktomorrow\Chief\Table\Filters\RadioFilter;
use Thinktomorrow\Chief\Table\Filters\ToggleFilter;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class FilterTableFixture
{
    public static function toggle(): Table
    {
        return static::base('toggle')
            ->filters([
                ToggleFilter::make('is_featured')
                    ->label('Uitgelicht')
                    ->optionLabel('Enkel uitgelicht')
                    ->optionDescription('Toont enkel de uitgelichte items'),
            ]);
    }

    public static function toggleWithoutOptionLabel(): Table
    {
        return static::base('toggleWithoutOptionLabel')
            ->filters([
                ToggleFilter::make('is_featured')->label('Uitgelicht'),
            ]);
    }

    public static function toggleOnByDefault(): Table
    {
        return static::base('toggleOnByDefault')
            ->filters([
                ToggleFilter::make('is_featured')->defaultOn(),
            ]);
    }

    public static function tertiaryToggle(): Table
    {
        return static::base('tertiaryToggle')
            ->filters([
                ToggleFilter::make('is_featured')
                    ->optionLabel('Enkel uitgelicht')
                    ->optionDescription('Toont enkel de uitgelichte items')
                    ->tertiary(),
            ]);
    }

    public static function tertiaryToggleWithoutOptionLabel(): Table
    {
        return static::base('tertiaryToggleWithoutOptionLabel')
            ->filters([
                ToggleFilter::make('is_featured')->label('Uitgelicht')->tertiary(),
            ]);
    }

    public static function primaryFilters(): Table
    {
        return static::base('primaryFilters')
            ->filters([
                ToggleFilter::make('is_featured')->optionLabel('Enkel uitgelicht')->primary(),
                CheckboxFilter::make('category')
                    ->label('Categorie')
                    ->options(['news' => 'Nieuws', 'blog' => 'Blog'])
                    ->primary(),
                RadioFilter::make('state')
                    ->label('Status')
                    ->options(['online' => 'Online', 'offline' => 'Offline'])
                    ->primary(),
            ]);
    }

    public static function tertiaryOptionFilters(): Table
    {
        return static::base('tertiaryOptionFilters')
            ->filters([
                CheckboxFilter::make('category')
                    ->label('Categorie')
                    ->options(['news' => 'Nieuws', 'blog' => 'Blog'])
                    ->tertiary(),
                RadioFilter::make('state')
                    ->label('Status')
                    ->options(['online' => 'Online', 'offline' => 'Offline'])
                    ->tertiary(),
            ]);
    }

    public static function checkbox(): Table
    {
        return static::base('checkbox')
            ->filters([
                CheckboxFilter::make('category')
                    ->label('Categorie')
                    ->options(['news' => 'Nieuws', 'blog' => 'Blog', 'event' => 'Evenement']),
            ]);
    }

    public static function radio(): Table
    {
        return static::base('radio')
            ->filters([
                RadioFilter::make('category')
                    ->label('Categorie')
                    ->options(['news' => 'Nieuws', 'newsletter' => 'Nieuwsbrief', 'blog' => 'Blog']),
            ]);
    }

    private static function base(string $method): Table
    {
        return Table::make()
            ->setTableReference(new TableReference(static::class, $method))
            ->query(fn () => FilterModelFixture::query())
            ->columns([
                ColumnText::make('title'),
            ]);
    }
}
