<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Testing\TestResponse;
use Thinktomorrow\Chief\Admin\Tags\TagGroupModel;
use Thinktomorrow\Chief\Admin\Tags\TagModel;

trait TagTestHelpers
{
    public function createTagModel(array $values = []): TagModel
    {
        return TagModel::create(array_merge([
            'color' => '#333333',
            'taggroup_id' => '666',
            'label' => 'in review',
        ], $values));
    }

    public function performTagStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.tags.store'), array_merge([
            'label' => 'reviewing',
            'color' => '#333333',
            'taggroup_id' => '1',
        ], $values));
    }

    public function performTagUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.tags.update', $tagId), array_merge([
            'label' => 'reviewed',
            'color' => '#666666',
            'taggroup_id' => '2',
        ], $values));
    }

    public function performTagDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.tags.delete', $tagId));
    }

    public function createTaggroupModel(array $values = []): TagGroupModel
    {
        return TagGroupModel::create(array_merge([
            'color' => '#333333',
            'label' => 'Review status',
        ], $values));
    }

    public function performTagGroupStore(array $values = []): TestResponse
    {
        return $this->asAdmin()->post(route('chief.taggroups.store'), array_merge([
            'label' => 'review states',
            'color' => '#333333',
            'taggroup_id' => '1',
        ], $values));
    }

    public function performTagGroupUpdate($tagId, array $values = []): TestResponse
    {
        return $this->asAdmin()->put(route('chief.taggroups.update', $tagId), array_merge([
            'label' => 'publication process',
            'color' => '#666666',
            'taggroup_id' => '2',
        ], $values));
    }

    public function performTagGroupDelete($tagId): TestResponse
    {
        return $this->asAdmin()->delete(route('chief.taggroups.delete', $tagId));
    }
}
