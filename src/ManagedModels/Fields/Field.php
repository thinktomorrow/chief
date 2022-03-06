<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\FieldType;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\ValidationParameters;

interface Field
{
    public function getType(): FieldType;

    public function ofType(...$type): bool;

    public function key(string $key): Field;

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

    /**
     * Render the field in a form.
     */
    public function render(array $viewData = []): string;

    /**
     * Render the field display on the page.
     */
    public function renderOnPage(array $viewData = []): string;

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

    public function customSaveMethod(string $method): Field;

    public function getCustomSaveMethod(): ?string;

    public function model($model): Field;
}
