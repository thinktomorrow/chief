<?php

namespace Thinktomorrow\Chief\Fields;

class RemainingFieldsTab extends FieldsTab
{
    private $fieldKeysBlacklist;

    public function __construct(string $title, array $fieldKeysBlacklist = [], string $view = null)
    {
       parent::__construct($title, [], $view);

       $this->fieldKeysBlacklist = $fieldKeysBlacklist;
    }

    public function withRemaining(array $fieldKeys): FieldsTab
    {
        return new FieldsTab($this->title, array_diff($fieldKeys, $this->fieldKeysBlacklist), $this->view);
    }
}
