<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

class FieldWindow
{
    private const EMPTY_ID = 'empty';

    private string $id;
    private Fields $fields;
    private array $fieldSetIds;
    private array $data;

    public function __construct(string $id, Fields $fields, array $fieldSetIds, array $data)
    {
        $this->id = $id;
        $this->fields = $fields;
        $this->fieldSetIds = $fieldSetIds;
        $this->data = array_merge(['title' => $id], $data);
    }

    public static function empty(): FieldWindow
    {
        return new static(static::EMPTY_ID, new Fields(), [], ['is_open' => false]);
    }

    public static function open(string $id): FieldWindow
    {
        return new static($id, new Fields(), [], ['is_open' => true]);
    }

    public function isOpen(): bool
    {
        return $this->data['is_open'] ?? false;
    }

    public static function close(): FieldWindow
    {
        return static::empty();
    }

    public function title(string $title): FieldWindow
    {
        return new static(
            $this->id,
            $this->fields,
            $this->fieldSetIds,
            array_merge($this->data, ['title' => $title]),
        );
    }

    public function getTitle(): string
    {
        return $this->data['title'] ?? '';
    }

    public function position(string $position)
    {
        return new static(
            $this->id,
            $this->fields,
            $this->fieldSetIds,
            array_merge($this->data, ['position' => $position]),
        );
    }

    public function getPosition(): string
    {
        return $this->data['position'] ?? 'sidebar';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEmpty(): bool
    {
        return $this->getId() === static::EMPTY_ID;
    }

    public function addFieldSet(FieldSet $fieldSet): FieldWindow
    {
        $fields = $this->fields->removeFieldSet($fieldSet->getId());

        return new static($this->id, $fields->add($fieldSet), $this->fieldSetIds, $this->data);
    }

    public function getFields(): Fields
    {
        return $this->fields;
    }

    public function addFieldSetId(string $fieldSetId): FieldWindow
    {
        return new static($this->id, $this->fields, array_merge($this->fieldSetIds, [$fieldSetId]), $this->data);
    }

    public function getFieldSetIds(): array
    {
        return $this->fieldSetIds;
    }
}
