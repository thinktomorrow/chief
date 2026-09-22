<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\MemoizedEloquentOptions;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class MemoizedEloquentOptionsTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('memoized_eloquent_options', function (Blueprint $table): void {
            $table->id();
            $table->string('slug');
            $table->string('title');
        });
    }

    public function test_options_are_queried_once_per_scope(): void
    {
        MemoizedEloquentOption::query()->create([
            'slug' => 'first',
            'title' => 'First',
        ]);

        DB::flushQueryLog();
        DB::enableQueryLog();

        $resolver = app(MemoizedEloquentOptions::class);

        $this->assertSame(['first' => 'First (nl)'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));
        $this->assertSame(['first' => 'First (nl)'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));
        $this->assertSame($resolver, app(MemoizedEloquentOptions::class));

        $optionQueries = collect(DB::getQueryLog())->filter(
            fn (array $query): bool => str_contains($query['query'], 'memoized_eloquent_options')
        );

        $this->assertCount(1, $optionQueries);
    }

    public function test_options_are_separated_by_locale(): void
    {
        MemoizedEloquentOption::query()->create([
            'slug' => 'first',
            'title' => 'First',
        ]);

        $resolver = app(MemoizedEloquentOptions::class);

        app()->setLocale('nl');
        $this->assertSame(['first' => 'First (nl)'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));

        app()->setLocale('en');
        $this->assertSame(['first' => 'First (en)'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));
    }

    public function test_options_are_separated_by_value_and_label_keys(): void
    {
        $option = MemoizedEloquentOption::query()->create([
            'slug' => 'first',
            'title' => 'First',
        ]);

        $resolver = app(MemoizedEloquentOptions::class);

        $this->assertSame(['first' => 'First (nl)'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));
        $this->assertSame([$option->id => 'First (nl)'], $resolver->getOptions(new MemoizedEloquentOption, 'id', 'title'));
        $this->assertSame(['first' => 'first'], $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'slug'));
    }

    public function test_a_new_scope_receives_fresh_options(): void
    {
        MemoizedEloquentOption::query()->create([
            'slug' => 'first',
            'title' => 'First',
        ]);

        $resolver = app(MemoizedEloquentOptions::class);

        $this->assertCount(1, $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));

        MemoizedEloquentOption::query()->create([
            'slug' => 'second',
            'title' => 'Second',
        ]);

        $this->assertCount(1, $resolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));

        $this->app->forgetScopedInstances();

        $freshResolver = app(MemoizedEloquentOptions::class);

        $this->assertNotSame($resolver, $freshResolver);
        $this->assertCount(2, $freshResolver->getOptions(new MemoizedEloquentOption, 'slug', 'title'));
    }
}

final class MemoizedEloquentOption extends Model
{
    public $timestamps = false;

    protected $table = 'memoized_eloquent_options';

    protected $guarded = [];

    protected function title(): Attribute
    {
        return Attribute::get(
            fn (string $value): string => $value.' ('.app()->getLocale().')'
        );
    }
}
