<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Validation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class NullValidator implements Validator
{

    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag()
    {
        return new MessageBag();
    }

    /**
     * Run the validator's rules against its data.
     *
     * @return array
     */
    public function validate()
    {
        return [];
    }

    /**
     * Get the attributes and values that were validated.
     *
     * @return array
     */
    public function validated()
    {
        return [];
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return false;
    }

    /**
     * Get the failed validation rules.
     *
     * @return array
     */
    public function failed()
    {
        return [];
    }

    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param  string|array $attribute
     * @param  string|array $rules
     * @param  callable $callback
     * @return $this
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        return $this;
    }

    /**
     * Add an after validation callback.
     *
     * @param  callable|string $callback
     * @return $this
     */
    public function after($callback)
    {
        return $this;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return new MessageBag();
    }
}
