<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\SharedFragmentDto;

class FormDto implements Wireable
{
    // Memoized fragment model
    private ?FragmentModel $fragmentModel = null;

    private function __construct(
        public string $fragmentId,
        public string $contextId,
        public ?string $parentId,
        public int $order,
        public string $label,
        public string $icon,
        public string $content,
        public bool $allowsFragments, // Has child fragments
        public bool $isOnline,
        public bool $isShared,
        public string $bookmark,
        public array $urls, // Front end urls for this fragment per locale
        public ContextDto $context,
        public Collection $fields,
        public Collection $sharedFragmentDtos,
    ) {}

    public static function fromForm(\Thinktomorrow\Chief\Forms\Layouts\Form $form, Model $model): self
    {
        $sharedFragmentDtos = self::composeSharedFragmentDtos($fragment->getFragmentId());

        return new static(
            $fragment->getFragmentId(),
            $context->id,
            $fragment->pivot->parent_id,
            $fragment->pivot->order,
            $fragment->getLabel(),
            $fragment->getIcon(),
            $fragment->renderInAdmin()->render(),
            count($fragment->allowedFragments()) > 0,
            $fragment->isOnline(),
            $fragment->isShared(),
            $fragment->getBookmark(),
            [], // TODO: provide urls per site
            $context,
            self::composeFields($fragment),
            $sharedFragmentDtos,
        );
    }

    private static function composeFields(Fragment $fragment): Collection
    {
        return collect(Layout::make($fragment->fields($fragment))
            ->getComponents())->map(fn ($form) => $form->getComponents())
            ->flatten();
    }

    private static function composeSharedFragmentDtos(string $fragmentId)
    {
        return app(ComposeLivewireDto::class)->getSharedFragmentDtos($fragmentId);
    }

    public function toLivewire()
    {
        return [
            'class' => static::class,
            'fragmentId' => $this->fragmentId,
            'contextId' => $this->contextId,
            'parentId' => $this->parentId,
            'order' => $this->order,
            'label' => $this->label,
            'icon' => $this->icon,
            'content' => $this->content,
            'allowsFragments' => $this->allowsFragments,
            'isOnline' => $this->isOnline,
            'isShared' => $this->isShared,
            'bookmark' => $this->bookmark,
            'urls' => $this->urls,
            'context' => $this->context->toLivewire(),
            'fields' => $this->fields->map->toLivewire(),
            'sharedFragmentDtos' => $this->sharedFragmentDtos->map->toLivewire(),
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['fragmentId'],
            $value['contextId'],
            $value['parentId'],
            $value['order'],
            $value['label'],
            $value['icon'],
            $value['content'],
            $value['allowsFragments'],
            $value['isOnline'],
            $value['isShared'],
            $value['bookmark'],
            $value['urls'],
            ContextDto::fromLivewire($value['context']),
            collect($value['fields'])->map(fn ($fieldData) => $fieldData['class']::fromLivewire($fieldData)),
            collect($value['sharedFragmentDtos'])->map(fn ($sharedFragmentDto) => SharedFragmentDto::fromLivewire($sharedFragmentDto)),
        );
    }

    public function getFragmentModel(): FragmentModel
    {
        if ($this->fragmentModel) {
            return $this->fragmentModel;
        }

        return $this->fragmentModel = FragmentModel::find($this->fragmentId);
    }

    public function getId(): string
    {
        return $this->contextId.'-'.$this->fragmentId.($this->parentId ? '-'.$this->parentId : '');
    }

    /** When isolating a fragment, it's id will change */
    public function changeFragmentId(string $fragmentId): self
    {
        $clone = clone $this;

        $clone->fragmentId = $fragmentId;

        return $clone;
    }

    public function changeOrder(int $order): self
    {
        $clone = clone $this;

        $clone->order = $order;

        return $clone;
    }

    public function changeOnlineState(bool $order): self
    {
        $clone = clone $this;

        $clone->isOnline = $order;

        return $clone;
    }
}
