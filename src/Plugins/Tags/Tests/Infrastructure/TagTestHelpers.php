<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure;

use Illuminate\Testing\TestResponse;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupModel;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;

trait TagTestHelpers
{
    protected function createTagModel(array $values = []): TagModel
    {
        return TagModel::create(array_merge([
            'color' => '#333333',
            'taggroup_id' => '666',
            'label' => 'in review',
        ], $values));
    }

    protected function performTagStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.tags.store'), array_merge([
            'label' => 'reviewing',
            'color' => '#333333',
            'taggroup_id' => '1',
        ], $values));
    }

    protected function performTagUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.tags.update', $tagId), array_merge([
            'label' => 'reviewed',
            'color' => '#666666',
            'taggroup_id' => '2',
        ], $values));
    }

    protected function performTagDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.tags.delete', $tagId));
    }

    protected function createTaggroupModel(array $values = []): TagGroupModel
    {
        return TagGroupModel::create(array_merge([
            'label' => 'Review status',
        ], $values));
    }

    protected function performTagGroupStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.taggroups.store'), array_merge([
            'label' => 'review states',
            'color' => '#333333',
            'taggroup_id' => '1',
        ], $values));
    }

    protected function performTagGroupUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.taggroups.update', $tagId), array_merge([
            'label' => 'publication process',
            'color' => '#666666',
            'taggroup_id' => '2',
        ], $values));
    }

    protected function performTagGroupDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.taggroups.delete', $tagId));
    }
}
