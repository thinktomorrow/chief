<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Validator;

interface Field
{
    public function getType(): FieldType;
    public function ofType(...$type): bool;
    public function getKey(): string;

    public function getColumn(): string;
    public function getName(string $locale = null): string;
    public function getValue(Model $model = null, string $locale = null);

    // LOCALIZATION
    public function locales(array $locales): Field;
    public function getLocales(): array;
    public function isLocalized(): bool;

    // VIEW
    public function getView(): string;
    public function getElementView(): string;
    public function getViewData(): array;

    // PRESENTATIONAL DATA
    public function getLabel(): ?string;
    public function getDescription(): ?string;
    public function getPrepend(): ?string;
    public function getAppend(): ?string;
    public function getPlaceholder(): ?string;

    // VALIDATION
    public function getValidation();
    public function hasValidation(): bool;
    public function getValidator(array $data): Validator;
    public function required(): bool;
    public function optional(): bool;


}
