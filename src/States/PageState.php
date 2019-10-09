<?php

namespace Thinktomorrow\Chief\States;

use Thinktomorrow\Chief\States\State\StateMachine;
use Thinktomorrow\Chief\States\State\StatefulContract;

class PageState extends StateMachine
{
    // Offline states
    const DRAFT = 'draft';
    const ARCHIVED = 'archived';
    const DELETED = 'deleted'; // soft deleted

    // Online states
    const PUBLISHED = 'published';

    protected $states = [
        self::DRAFT,
        self::ARCHIVED,
        self::DELETED,

        self::PUBLISHED,
    ];

    protected $transitions = [
        'publish' => [
            'from' => [self::DRAFT],
            'to'   => self::PUBLISHED,
        ],
        'unpublish' => [
            'from' => [self::PUBLISHED],
            'to'   => self::DRAFT,
        ],
        'archive' => [
            'from' => [self::PUBLISHED, self::DRAFT],
            'to'   => self::ARCHIVED,
        ],
        'unarchive' => [
            'from' => [self::ARCHIVED],
            'to'   => self::DRAFT,
        ],
        'delete' => [
            'from' => [self::ARCHIVED, self::DRAFT],
            'to'   => self::DELETED,
        ],
    ];

    public function __construct(StatefulContract $order)
    {
        parent::__construct($order);
    }

    public function isOffline(): bool
    {
        return in_array($this->statefulContract->state(), [
            static::DRAFT,
            static::ARCHIVED,
            static::DELETED,
        ]);
    }

    public function isOnline(): bool
    {
        return in_array($this->statefulContract->state(), [
            static::PUBLISHED,
        ]);
    }
}
