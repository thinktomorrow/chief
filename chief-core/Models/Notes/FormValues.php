<?php

namespace Thinktomorrow\Chief\Models\Notes;

/**
 * DTO for the admin form
 */
class FormValues
{
    /**
     * @var \Optiphar\Promos\Common\Repositories\Database\PromoModel
     */
    private $model;

    public function __construct(Note $model)
    {
        $this->model = $model;
    }

    public function type()
    {
        return $this->model->type;
    }

    public function level()
    {
        return $this->model->level;
    }

    public function startAt()
    {
        if ($startAt = $this->model->start_at) {
            return $startAt->format('Y-m-d');
        }

        return null;
    }

    public function endAt()
    {
        if ($endAt = $this->model->end_at) {
            return $endAt->format('Y-m-d');
        }

        return null;
    }

    public function trans($locale, $attribute)
    {
        return $this->model->getTranslationFor($attribute, $locale);
    }

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }
    }
}
