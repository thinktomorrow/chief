<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;

class PageState extends StateMachine
{
    // default column key that refers to the current state in db
    const KEY = 'current_state';

    // Offline states
    const DRAFT = 'draft';
    const ARCHIVED = 'archived';
    const DELETED = 'deleted'; // soft deleted

    // Online states
    const PUBLISHED = 'published';

    protected array $states = [
        self::DRAFT,
        self::ARCHIVED,
        self::DELETED,

        self::PUBLISHED,
    ];

    protected array $transitions = [
        'publish' => [
            'from' => [self::DRAFT],
            'to' => self::PUBLISHED,
        ],
        'unpublish' => [
            'from' => [self::PUBLISHED],
            'to' => self::DRAFT,
        ],
        'archive' => [
            'from' => [self::PUBLISHED, self::DRAFT],
            'to' => self::ARCHIVED,
        ],
        'unarchive' => [
            'from' => [self::ARCHIVED],
            'to' => self::DRAFT,
        ],
        'delete' => [
            'from' => [self::ARCHIVED, self::DRAFT],
            'to' => self::DELETED,
        ],
    ];

    /**
     * @param WithPageState $model
     * @return static
     */
    public static function make(WithPageState $model): self
    {
        return new static($model, $model->getPageStateAttribute());
    }

    public function isOffline(): bool
    {
        return in_array($this->statefulContract->stateOf($this->statefulContract->getPageStateAttribute()), [
            static::DRAFT,
            static::ARCHIVED,
            static::DELETED,
        ]);
    }

    public function isOnline(): bool
    {
        return in_array($this->statefulContract->stateOf($this->statefulContract->getPageStateAttribute()), [
            static::PUBLISHED,
        ]);
    }
}
