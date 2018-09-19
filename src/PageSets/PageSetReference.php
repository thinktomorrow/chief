<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\PageSets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Common\FlatReferences\ProvidesFlatReference;

class PageSetReference implements ProvidesFlatReference
{
    /** @var string */
    private $key;

    /** @var string */
    private $action;

    /** @var array */
    private $parameters;

    /** @var string */
    private $label;

    public function __construct(string $key, string $action, array $parameters = [], string $label = null)
    {
        $this->key = $key;
        $this->action = $action;
        $this->parameters = $parameters;
        $this->label = $label;
    }

    public static function fromArray(string $key, array $values): PageSetReference
    {
        // Constraints
        if (!isset($values['action'])) {
            throw new \InvalidArgumentException('Pageset reference array is missing required values for the "action" keys. Given: ' . print_r($values, true));
        }

        return new static(
            $key,
            $values['action'],
            $values['parameters'] ?? [],
            $values['label'] ?? null
        );
    }

    public static function all(): Collection
    {
        $sets = config('thinktomorrow.chief.pagesets', []);

        return collect($sets)->map(function ($set, $key) {
            return PageSetReference::fromArray($key, $set);
        });
    }

    public static function find($key): ?PageSetReference
    {
        return static::all()->filter(function ($ref) use ($key) {
            return $ref->key() == $key;
        })->first();
    }

    /**
     * Run the query and collect the resulting pages into a PageSet object.
     * @return PageSet
     */
    public function toPageSet(): PageSet
    {
        // Reconstitute the action - optional @ ->defaults to the name of the pageset e.g. @upcoming
        list($class, $method) = $this->parseAction($this->action, camel_case($this->key));

        $this->validateAction($class, $method);

        $result = call_user_func_array([app($class),$method], $this->parameters);

        if (! $result instanceof PageSet && $result instanceof Collection) {
            return new PageSet($result->all(), $this->key);
        }

        return $result;
    }

    public function store()
    {
        return StoredPageSetReference::create([
            'key'        => $this->key,
            'action'     => $this->action,
            'parameters' => $this->parameters,
        ]);
    }

    public function key()
    {
        return $this->key;
    }

    private static function parseAction($action, $default_method = '__invoke'): array
    {
        if (false !== strpos($action, '@')) {
            return explode('@', $action);
        }

        return [$action, $default_method];
    }

    private static function validateAction($class, $method)
    {
        if (! class_exists($class)) {
            throw new \InvalidArgumentException('The class ['.$class.'] isn\'t a valid class reference or does not exist in the chief-settings.pagesets config entry.');
        }

        if (!method_exists($class, $method)) {
            throw new \InvalidArgumentException('The method ['.$method.'] does not exist on the class ['.$class.']. Make sure you provide a valid method to the action value in the chief-settings.pagesets config entry.');
        }
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->key);
    }

    public function flatReferenceLabel(): string
    {
        return $this->label ?? $this->key;
    }

    public function flatReferenceGroup(): string
    {
        return 'pageset';
    }
}
