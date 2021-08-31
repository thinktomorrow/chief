<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use Illuminate\Support\Str;

class FieldWindow
{
    private const EMPTY_ID = 'empty';

    private string $id;
    private array $data;
    private Fields $fields;
    private array $fieldGroupIds;
    private bool $isOpen;

    public function __construct(string $id, array $data, Fields $fields, array $fieldGroupIds, bool $isOpen = false)
    {
        $this->id = $id;
        $this->data = array_merge(['title' => $id], $data);
        $this->fields = $fields;
        $this->fieldGroupIds = $fieldGroupIds;
        $this->isOpen = $isOpen;
    }

    public static function empty(): FieldWindow
    {
        return new static(static::EMPTY_ID, [], new Fields(), [],false);
    }

    public static function open(?string $id = null): FieldWindow
    {
        if (null === $id) {
            $id = Str::random(8);
        }

        return new static($id, [], new Fields(), [],true);
    }

    public function title(string $title): FieldWindow
    {
        return new static($this->id, array_merge($this->data, ['title' => $title]), $this->fields,$this->fieldGroupIds, $this->isOpen);
    }

    public function getTitle(): ?string
    {
        return $this->data['title'] ?? '';
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public static function close(): FieldWindow
    {
        return static::empty();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEmpty(): bool
    {
        return $this->getId() === static::EMPTY_ID;
    }

    public function addFieldGroup(FieldGroup $fieldGroup): FieldWindow
    {
        $fields = $this->fields->removeFieldGroup($fieldGroup->getId());

        return new static($this->id, $this->data, $fields->add($fieldGroup), $this->fieldGroupIds, $this->isOpen);
    }

    public function getFields(): Fields
    {
        return $this->fields;
    }

    public function addFieldGroupId(string $fieldGroupId): FieldWindow
    {
        return new static($this->id, $this->data, $this->fields, array_merge($this->fieldGroupIds, [$fieldGroupId]), $this->isOpen);
    }

    public function getFieldGroupIds(): array
    {
        return $this->fieldGroupIds;
    }
}
