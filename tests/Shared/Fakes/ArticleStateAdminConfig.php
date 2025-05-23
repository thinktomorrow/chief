<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfigDefaults;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class ArticleStateAdminConfig implements StateAdminConfig
{
    use StateAdminConfigDefaults;

    public function getStateKey(): string
    {
        return 'article_state';
    }

    public function getStates(): array
    {
        return [
            ArticleState::offline,
            ArticleState::online,
        ];
    }

    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => [ArticleState::offline],
                'to' => ArticleState::online,
            ],
            'draft' => [
                'from' => [ArticleState::online],
                'to' => ArticleState::offline,
            ],
        ];
    }

    public function getConfirmationFields(StatefulContract $statefulContract, string $transitionKey): iterable
    {
        if ($transitionKey == 'draft') {
            yield Text::make('draft_note')->required();
        }

        return [];
    }
}
