<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin;

use Illuminate\Support\Str;

final class AdminConfig
{
    private const PAGINATION = 16;

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
        $singular = Str::of(class_basename($model))->singular()->replace('_', ' ')->__toString();
        $plural = Str::of(class_basename($model))->plural()->replace('_', ' ')->__toString();

        $this->indexTitle($plural);
        $this->pageTitle($model->title ?? $singular);
        $this->modelName($singular);

        if(isset($model->created_at) && $model->created_at) {
            $this->rowContent('<div><span class="text-grey-300 text-sm">'.$model->created_at->format('d/m/Y').'</span></div>');
        }

        if(public_method_exists($model, 'onlineStatusAsLabel')) {
            $this->rowBadge($model->onlineStatusAsLabel());
        }

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
        return $this->get('model.nav', $this->getModelName());
    }

    public function indexTitle(string $indexTitle): self
    {
        return $this->set('index.title', $indexTitle);
    }

    public function getIndexTitle(): string
    {
        return $this->get('index.title', '');
    }

    public function pageTitle(string $pageTitle): self
    {
        return $this->set('page.title', $pageTitle);
    }

    public function getPageTitle(): string
    {
        return $this->get('page.title', '');
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

    public function rowBadge(string $rowBadge): self
    {
        return $this->set('row.badge', $rowBadge);
    }

    public function getRowBadge(): string
    {
        return $this->get('row.badge', '');
    }

    private function set(string $key, $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }

    private function get(string $key, $default = null)
    {
        if(!array_key_exists($key, $this->config)) return $default;

        return $this->config[$key];
    }
}
