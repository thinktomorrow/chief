<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragment;

final class GetMoveDestinations
{
    public function __construct(
        private FragmentRepository $fragments,
        private ContextOwnerRepository $owners,
    ) {}

    /** @return list<array{id: string, label: string, depth: int, reason: ?string}> */
    public function handle(string $contextId, string $fragmentId, string $locale): array
    {
        $fragment = $this->fragments->findInContext($fragmentId, $contextId);
        $fragmentClass = $fragment::class;
        $currentParentId = $fragment->getFragmentModel()->pivot->parent_id;
        $owner = $this->owners->findOwner($contextId);

        $destinations = [[
            'id' => 'root',
            'label' => 'Als topfragment',
            'depth' => 0,
            'reason' => $currentParentId === null
                ? 'current-location'
                : (! in_array($fragmentClass, $owner->allowedFragments(), true) ? 'not-allowed-at-root' : null),
        ]];

        $append = function ($nodes, int $depth, bool $belowMovingFragment) use (&$append, &$destinations, $fragmentId, $fragmentClass, $currentParentId, $locale, $owner): ?string {
            $firstDescription = null;

            foreach ($nodes as $candidate) {
                $id = $candidate->getFragmentId();
                $isMovingFragment = $id === $fragmentId;

                $reason = match (true) {
                    $isMovingFragment => 'self',
                    $belowMovingFragment => 'descendant',
                    $id === $currentParentId => 'current-location',
                    ! in_array($fragmentClass, $candidate->allowedFragments(), true) => 'not-allowed',
                    default => null,
                };

                $index = count($destinations);
                $destinations[] = [
                    'id' => $id,
                    'label' => $candidate->getLabel(),
                    'depth' => $depth,
                    'reason' => $reason,
                ];

                $candidate->setContextOwner($owner);
                $ownDescription = $this->ownDescription($candidate, $locale);
                $childDescription = $append($candidate->getChildNodes(), $depth + 1, $belowMovingFragment || $isMovingFragment);
                $description = $ownDescription ?? $childDescription;

                if ($description !== null) {
                    $destinations[$index]['label'] .= ' — '.Str::limit($description, 72);
                }

                $firstDescription ??= $description;
            }

            return $firstDescription;
        };

        $append($this->fragments->getFragmentCollection($contextId), 0, false);

        return $destinations;
    }

    private function ownDescription(Fragment $fragment, string $locale): ?string
    {
        $fields = PageLayout::make($fragment->fields($fragment))
            ->model($fragment->getFragmentModel())
            ->getFields();

        $firstText = null;

        foreach ($fields as $field) {
            if (! $field instanceof Text && ! $field instanceof Textarea) {
                continue;
            }

            $value = $field->getValueOrFallback($locale);

            if (! is_string($value)) {
                continue;
            }

            $text = Str::squish(strip_tags(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')));

            if ($text === '') {
                continue;
            }

            if (preg_match('/(^|[._-])(title|titel|heading|headline|kop)([._-]|$)/i', $field->getKey())) {
                return $text;
            }

            $firstText ??= $text;
        }

        return $firstText;
    }
}
