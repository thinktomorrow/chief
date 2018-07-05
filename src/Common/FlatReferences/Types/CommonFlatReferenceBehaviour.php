<?php

namespace Thinktomorrow\Chief\Common\FlatReferences\Types;

use Illuminate\Database\Eloquent\Model;

trait CommonFlatReferenceBehaviour
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
     * @return Model
     */
    public function instance(): Model
    {
        return (new $this->className)->findOrFail($this->id);
    }

    public function get(): string
    {
        return $this->className.'@'.$this->id;
    }

    public function equals($other): bool
    {
        return (get_class($this) === get_class($other) && $this->get() === $other->get());
    }

    public function __toString()
    {
        return $this->get();
    }
}
