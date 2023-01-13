<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Common\Localizable;
use Thinktomorrow\Chief\Forms\Fields\Validation\Validatable;

interface Field extends Validatable, Localizable
{
    public function key(string $key): static;
    public function getKey(): string;

    // Formgroup elements
    public function id(string $id): static;
    public function getId(?string $locale = null): string;
    public function name(string $name): static;
    public function getName(?string $locale = null): string;
    public function label(string $label): static;
    public function getLabel(): ?string;
    public function description(string $description): static;
    public function getDescription(): ?string;

    // Saving and retrieving field values
    public function model(Model $model): static;
    public function getModel(): ?Model;

    public function columnName(string $columnName): static;
    public function getColumnName(): string;

    public function prepare(Closure $prepareModelValue): static;
    public function hasPrepareModelValue(): bool;
    public function getPrepareModelValue(): ?Closure;

    public function setModelValue(Closure $setModelValue): static;
    public function hasSetModelValue(): bool;
    public function getSetModelValue(): ?Closure;

    public function save(Closure $save): static;
    public function hasSave(): bool;
    public function getSave(): ?Closure;

    /** Value of active form request */
    public function getActiveValue(?string $locale = null);
    public function value(mixed $value): static;
    public function getValue(?string $locale = null): mixed;

    public function default(null|string|int|array|Closure $default): static;
    public function getDefault(?string $locale = null): null|string|int|array;

    public function tagged(string|array $tags): bool;
    public function untagged(): bool;
    public function tag(string|array $tags): static;
    public function untag(string|array $tags): static;

    public function toggleField(string $fieldName, string|array $values): static;
    public function getFieldToggles(): array;

    // TODO: no more getTYPE: conditional fields now still depend on it...
//    public function getType(): FieldType;
//
//    public function ofType(...$type): bool;
//
//    public function key(string $key): Field;
//
//    public function getKey(): string;
//
//    public function getViewKey(): string;
//
//    public function getId(string $locale = null): string;
//
//    public function getColumn(): string;
//
//    public function getName(string $locale = null): string;
//
//    public function getDottedName(string $locale = null): string;
//
//    public function getValue(string $locale = null);
//
//    public function getLocales(): array;
//
//    public function locales(array $locales = null);
//
//    public function isLocalized(): bool;
//
//    /**
//     * Render the field in a form.
//     */
//    public function render(array $viewData = []): string;
//
//    /**
//     * Render the field display on the page.
//     */
//    public function renderOnPage(array $viewData = []): string;
//
//    public function getLabel(): ?string;
//
//    public function getDescription(): ?string;
//
//    public function getPrepend(?string $locale = null): ?string;
//
//    public function getAppend(?string $locale = null): ?string;
//
//    public function getPlaceholder(?string $locale = null): ?string;
//
//    public function getValidationNames(): array;
//
//    public function getValidationParameters(): ValidationParameters;
//
//    public function hasValidation(): bool;
//
//    public function required(): bool;
//
//    public function optional(): bool;
//
//    public function customSaveMethod(string $method): Field;
//
//    public function getCustomSaveMethod(): ?string;
//
//    public function model($model): Field;
}
