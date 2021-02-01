<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\States\PageState;

final class PublishAssistantTest extends ChiefTestCase
{
    /** @test */
    public function a_manager_can_publish_a_model()
    {
        $model = $this->setupAndCreateArticle([PageState::KEY => PageState::DRAFT]);
        $manager = $this->manager($model);

        $this->asAdmin()->post(
            $manager->route('publish', $model)
        );

        $this->assertTrue($model->fresh()->isPublished());
    }

    /** @test */
    public function a_manager_can_unpublish_a_model()
    {
        $model = $this->setupAndCreateArticle([PageState::KEY => PageState::PUBLISHED]);
        $manager = $this->manager($model);

        $this->asAdmin()->post(
            $manager->route('unpublish', $model)
        );

        $this->assertFalse($model->fresh()->isPublished());
    }
}
