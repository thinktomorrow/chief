<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\App\Queries\GetOwners;
use Thinktomorrow\Chief\Fragments\Fragment;

class FragmentDto implements Wireable
{
    public function __construct(
        public string $fragmentId,
        public string $contextId,
        public string $label,
        public string $content,
        public bool $isOnline,
        public bool $isShared,
        public string $bookmark,
        public array $urls, // Front end urls for this fragment per locale
        public Collection $fields,
        public Collection $sharedFragmentDtos,
    ) {}

    public static function fromFragment(Fragment $fragment, ContextDto $context): self
    {
        $fields = self::composeFields($fragment);
        $sharedFragmentDtos = self::composeSharedFragmentDtos($fragment->getFragmentId());

        return new static(
            $fragment->getFragmentId(),
            $context->contextId,
            $fragment->getLabel(),
            $fragment->renderInAdmin()->render(),
            $fragment->isOnline(),
            $fragment->isShared(),
            $fragment->getBookmark(),
            [], // TODO: provide urls per site
            $fields->all(),
            $sharedFragmentDtos,
        );
    }

    private static function composeFields(Fragment $fragment): Fields
    {
        return Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->getFragmentModel())
            ->getFields();
    }

    private static function composeSharedFragmentDtos(string $fragmentId)
    {
        return app(GetOwners::class)->getSharedFragmentDtos($fragmentId);
    }

    public function toLivewire()
    {
        return [
            'class' => static::class,
            'fragmentId' => $this->fragmentId,
            'contextId' => $this->contextId,
            'label' => $this->label,
            'content' => $this->content,
            'isOnline' => $this->isOnline,
            'isShared' => $this->isShared,
            'bookmark' => $this->bookmark,
            'urls' => $this->urls,
            'fields' => $this->fields->map->toLivewire(),
            'sharedFragmentDtos' => $this->sharedFragmentDtos->map->toLivewire(),
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['fragmentId'],
            $value['contextId'],
            $value['label'],
            $value['content'],
            $value['isOnline'],
            $value['isShared'],
            $value['bookmark'],
            $value['urls'],
            $value['fields']->map(fn ($fieldData) => $fieldData['class']::fromLivewire($fieldData)),
            $value['sharedFragmentDtos']->map(fn ($sharedFragmentDto) => SharedFragmentDto::fromLivewire($sharedFragmentDto)),
        );
    }
}
