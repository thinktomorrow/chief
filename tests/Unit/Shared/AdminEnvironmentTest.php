<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Thinktomorrow\Chief\Shared\AdminEnvironment;
use Thinktomorrow\Chief\Tests\TestCase;

final class AdminEnvironmentTest extends TestCase
{
    #[DataProvider('requestPathProvider')]
    public function test_it_detects_admin_and_livewire_request_paths(string $path, bool $expected): void
    {
        $application = Mockery::mock(Application::class);
        $application->shouldReceive('runningInConsole')->once()->andReturnFalse();

        $adminEnvironment = new AdminEnvironment($application);

        $this->assertSame($expected, $adminEnvironment->check(Request::create($path)));
    }

    public static function requestPathProvider(): iterable
    {
        yield 'admin root' => ['/admin', true];
        yield 'admin page' => ['/admin/pages', true];
        yield 'legacy Livewire update' => ['/livewire/update', true];
        yield 'hashed Livewire update' => ['/livewire-a1b2c3d4/update', true];
        yield 'hashed Livewire upload' => ['/livewire-a1b2c3d4/upload-file', true];
        yield 'hashed Livewire script' => ['/livewire-a1b2c3d4/livewire.min.js', true];
        yield 'public page' => ['/products', false];
        yield 'similar admin prefix' => ['/administrator', false];
        yield 'similar Livewire prefix' => ['/livewire-showcase', false];
        yield 'invalid Livewire hash' => ['/livewire-chiefhash/update', false];
        yield 'missing Livewire endpoint' => ['/livewire-a1b2c3d4', false];
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
