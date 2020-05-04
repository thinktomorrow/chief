<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

class FieldReference
{
    /** @var string */
    private $managerKey;

    /** @var string */
    private $fieldKey;

    /** @var string */
    private $fragmentKey;

    public function __construct(string $managerKey, string $fieldKey, string $fragmentKey = null)
    {
        $this->managerKey = $managerKey;
        $this->fieldKey = $fieldKey;
        $this->fragmentKey = $fragmentKey;
    }

    public function getManagerKey(): string
    {
        return $this->managerKey;
    }

    public function getFieldKey(): string
    {
        return $this->fieldKey;
    }

    public function getFragmentKey(): ?string
    {
        return $this->fragmentKey;
    }

    public function hasFragmentKey(): bool
    {
        return isset($this->fragmentKey);
    }

    public function toArray(): array
    {
        return [
            'managerKey'  => $this->managerKey,
            'fieldKey'    => $this->fieldKey,
            'fragmentKey' => $this->fragmentKey,
        ];
    }
}
