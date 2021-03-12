<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ArchiveAssistantTest extends ChiefTestCase
{
    /** @test */
    public function a_manager_can_archive_a_model()
    {
        $model = $this->setupAndCreateArticle();
        $manager = $this->manager($model);

        $this->asAdmin()->post($manager->route('archive', $model));

        $this->assertTrue($model->fresh()->isArchived());
    }

    /** @test */
    public function a_manager_can_unarchive_a_model()
    {
        $model = $this->setupAndCreateArticle([PageState::KEY => PageState::ARCHIVED]);
        $manager = $this->manager($model);

        $this->asAdmin()->post($manager->route('unarchive', $model));

        $this->assertFalse($model->fresh()->isArchived());
    }

    /** @test */
    public function the_archive_index_can_be_visited_when_there_is_an_archived_model()
    {
        $model = $this->setupAndCreateArticle([PageState::KEY => PageState::ARCHIVED]);
        $manager = $this->manager($model);

        auth('chief')->login($this->admin());

        $this->assertTrue($manager->can('archive_index'));
    }

    /** @test */
    public function the_archive_index_cannot_be_visited_when_there_are_no_archived_models()
    {
        $model = $this->setupAndCreateArticle();
        $manager = $this->manager($model);

        auth('chief')->login($this->admin());

        $this->assertFalse($manager->can('archive_index'));
    }
}
