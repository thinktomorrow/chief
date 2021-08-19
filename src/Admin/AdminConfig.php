<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

final class AdminConfig
{
    private const PAGINATION = 12;

    private array $config;

    private function __construct()
    {
        $this->config = [];
    }

    public static function make()
    {
        return new static();
    }

    public function defaults($model): self
    {
        $singular = Str::of(class_basename($model))->singular()->snake()->replace('_', ' ')->__toString();
        $plural = Str::of(class_basename($model))->plural()->snake()->replace('_', ' ')->__toString();

        $this->indexTitle($plural);
        $this->pageTitle($model->title ?? $singular);
        $this->modelName($singular);

        $this->rowContent(view('chief::manager._index._default-row-content', ['model' => $model])->render());

        return $this;
    }

    public function paginate($perPage = self::PAGINATION): self
    {
        return $this->set('pagination', $perPage);
    }

    public function noPagination(): self
    {
        return $this->set('pagination', 0);
    }

    public function getPagination(): int
    {
        return $this->get('pagination', self::PAGINATION);
    }

    public function modelName(string $modelName): self
    {
        return $this->set('model.name', $modelName);
    }

    public function getModelName(): string
    {
        return $this->get('model.name', '');
    }

    public function navTitle(string $navTitle): self
    {
        return $this->set('model.nav', $navTitle);
    }

    public function getNavTitle(): string
    {
        return $this->get('model.nav', $this->getIndexTitle());
    }

    public function navIcon(string $navIcon): self
    {
        return $this->set('model.navIcon', $navIcon);
    }

    public function getNavIcon(): string
    {
        return $this->get('model.navIcon', 'icon-collection');
    }

    public function indexTitle(string $indexTitle): self
    {
        return $this->set('index.title', $indexTitle);
    }

    public function getIndexTitle(): string
    {
        return strip_tags($this->get('index.title', ''));
    }

    public function pageTitle(string $pageTitle): self
    {
        return $this->set('page.title', $pageTitle);
    }

    public function getPageTitle(): string
    {
        return strip_tags($this->get('page.title', ''));
    }

    public function indexBreadCrumb(string $url, string $label): self
    {
        return $this->set('page.indexbreadcrumb', [
            'url' => $url,
            'label' => $label,
        ]);
    }

    public function getIndexBreadCrumb(): ?object
    {
        if(!$this->get('page.indexbreadcrumb')) return null;

        return (object) $this->get('page.indexbreadcrumb');
    }

    public function rowTitle(string $rowTitle): self
    {
        return $this->set('row.title', $rowTitle);
    }

    public function getRowTitle(): string
    {
        return $this->get('row.title', $this->getPageTitle());
    }

    public function rowContent(string $rowContent): self
    {
        return $this->set('row.content', $rowContent);
    }

    public function getRowContent(): string
    {
        return $this->get('row.content', '');
    }

    private function set(string $key, $value): self
    {
        Arr::set($this->config, $key, $value);

        return $this;
    }

    private function get(string $key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }
}
