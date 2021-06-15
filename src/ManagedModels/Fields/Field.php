<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\FieldType;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\ValidationParameters;

interface Field
{
    public function getType(): FieldType;

    public function ofType(...$type): bool;

    public function getKey(): string;

    public function getViewKey(): string;

    public function getId(string $locale = null): string;

    public function getColumn(): string;

    public function getName(string $locale = null): string;

    public function getDottedName(string $locale = null): string;

    public function getValue(string $locale = null);

    public function getLocales(): array;

    public function locales(array $locales = null);

    public function isLocalized(): bool;

    public function render(array $viewData = []): string;

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
