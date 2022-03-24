<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Links;

class Link
{
    private string $modelType;
    private string $modelId;
    private string $label;
    private string $locale;
    private string $slug;

    public function __construct(string $modelType, string $modelId, string $label, string $locale, string $slug)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->label = $label;
        $this->locale = $locale;
        $this->slug = $slug;
    }

    public static function fromMappedData(\stdClass $record): static
    {
        return new static(
            $record->model_type,
            (string) $record->model_id,
            (string) $record->internal_label,
            $record->locale,
            $record->slug
        );
    }

//    public function
}
