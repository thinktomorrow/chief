<?php

namespace Thinktomorrow\Chief\Urls\App\Repositories;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UrlRepository
{
    public function create(ModelReference $modelReference, array $values): string
    {
        if (empty($values['site']) || empty($values['slug']) || empty($values['status'])) {
            throw new \InvalidArgumentException('Site, slug and status is required');
        }

        if ($this->findActiveUrlBySlug($values['slug'], $values['site'])) {
            throw new UrlAlreadyExists('Slug ['.$values['slug'].'] for site ['.$values['site'].'] already exists for another record');
        }

        $record = UrlRecord::create(array_merge([
            'model_type' => $modelReference->shortClassName(),
            'model_id' => $modelReference->id(),
        ], $values));

        return (string) $record->id;
    }

    public function update(string $id, array $values): void
    {
        if (empty($values['slug']) || empty($values['status'])) {
            throw new \InvalidArgumentException('Slug and status is required');
        }

        $record = $this->find($id);

        if (($existingRecord = $this->findActiveUrlBySlug($values['slug'], $record->site)) && $existingRecord->id !== $record->id) {
            throw new UrlAlreadyExists('Slug ['.$values['slug'].'] for site ['.$record->site.'] already exists for another record');
        }

        $record->update($values);
    }

    public function find(string $id): UrlRecord
    {
        return UrlRecord::findOrFail($id);
    }

    public function findBySlug(string $slug, string $site): ?UrlRecord
    {
        return UrlRecord::where('slug', $slug)
            ->where('site', $site)
            ->first();
    }

    public function findActiveByModel(ModelReference $modelReference, string $site): ?UrlRecord
    {
        return UrlRecord::where('model_type', $modelReference->shortClassName())
            ->where('model_id', $modelReference->id())
            ->whereNull('redirect_id')
            ->where('site', $site)
            ->first();
    }

    public function findRecentRedirectByModel(ModelReference $modelReference, string $site): ?UrlRecord
    {
        return UrlRecord::where('model_type', $modelReference->shortClassName())
            ->where('model_id', $modelReference->id())
            ->whereNotNull('redirect_id')
            ->where('site', $site)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function findActiveUrlBySlug(string $slug, string $site): ?UrlRecord
    {
        return UrlRecord::where('slug', $slug)
            ->where('site', $site)
            ->whereNull('redirect_id')
            ->first();
    }

    public function getAllRedirects(ModelReference $modelReference): Collection
    {
        return UrlRecord::where('model_type', $modelReference->shortClassName())
            ->where('model_id', $modelReference->id())
            ->whereNotNull('redirect_id')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getIdenticalUrlsOfModel(ModelReference $modelReference, string $slug, string $site, ?int $whiteListedId): Collection
    {
        return UrlRecord::where('slug', $slug)
            ->when($whiteListedId, function ($query, $whiteListedId) {
                return $query->whereNot('id', $whiteListedId);
            })
            ->where('site', $site)
            ->where('model_type', $modelReference->shortClassName())
            ->where('model_id', $modelReference->id())
            ->get();
    }

    public function getIdenticalUrlsOfOtherModels(ModelReference $modelReference, string $slug, string $site): Collection
    {
        return UrlRecord::where('slug', $slug)
            ->where('site', $site)
            ->whereNot(function ($query) use ($modelReference) {
                $query->where('model_type', $modelReference->shortClassName())
                    ->where('model_id', $modelReference->id());
            })
            ->get();
    }
}
