<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\FlatReferences;

use Illuminate\Database\Eloquent\Model;

class FlatReference
{
    /** @var string */
    protected $className;

    protected $id;

    public function __construct(string $className, $id)
    {
        $this->className = $className;
        $this->id = $id;
    }

    /**
     * Recreate the model instance that is referred to by this collection id
     *
     * @return Model
     */
    public function instance(): Model
    {
        return (new $this->className)->withoutGlobalScopes()->findOrFail($this->id);
    }

    public function id()
    {
        return $this->id;
    }

    public function className(): string
    {
        return $this->className;
    }

    public function get(): string
    {
        return $this->className . '@' . $this->id;
    }

    public function equals($other): bool
    {
        return (get_class($this) === get_class($other) && $this->get() === $other->get());
    }

    public function is(string $flatReferenceString): bool
    {
        return $this->get() === $flatReferenceString;
    }

    public function __toString()
    {
        return $this->get();
    }
}
