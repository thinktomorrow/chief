<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use InvalidArgumentException;

final readonly class RichData
{
    /** @param array<string, mixed> $metadata */
    private function __construct(
        public string $type,
        public ?string $content = null,
        public array $metadata = [],
        public ?int $model = null,
        public ?string $disk = null,
        public ?string $path = null,
    ) {
        if ($model !== null && $model < 0) {
            throw new InvalidArgumentException('Invalid audit model position.');
        }

        AuditEventDTO::assertContext($metadata);

        if ($type === 'reference' && (! $disk || ! $path || str_contains($path, '..') || str_starts_with($path, '/') || ! array_key_exists($disk, config('filesystems.disks', [])))) {
            throw new InvalidArgumentException('Invalid audit reference.');
        }
    }

    public static function text(string $content, ?int $model = null): self
    {
        return new self('text', $content, model: $model);
    }

    public static function html(string $content, ?int $model = null): self
    {
        return new self('html', $content, model: $model);
    }

    /** @param array<string, mixed> $metadata */
    public static function metadata(array $metadata, ?int $model = null): self
    {
        return new self('metadata', metadata: $metadata, model: $model);
    }

    /** @param array<string, mixed> $metadata */
    public static function mailPreview(string $html, array $metadata = [], ?int $model = null): self
    {
        return new self('mailpreview', $html, $metadata, $model);
    }

    public static function reference(string $name, string $disk, string $path, ?int $model = null): self
    {
        return new self('reference', metadata: ['name' => $name], model: $model, disk: $disk, path: $path);
    }
}
