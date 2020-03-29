<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\Chief\Fields\Validation\ValidationParameters;

interface Field
{
    public function getType(): FieldType;

    public function ofType(...$type): bool;

    public function getKey(): string;

    public function getColumn(): string;

    public function getName(string $locale = null): string;

    public function getValue(string $locale = null);

    public function getLocales(): array;

    public function isLocalized(): bool;

    public function render(?string $locale = null): string;

    public function getLabel(): ?string;

    public function getDescription(): ?string;

    public function getPrepend(?string $locale = null): ?string;

    public function getAppend(?string $locale = null): ?string;

    public function getPlaceholder(?string $locale = null): ?string;

    public function getValidationNames(): array;

    public function getValidationParameters(): ValidationParameters;

    public function hasValidation(): bool;

    public function required(): bool;

    public function optional(): bool;
}
