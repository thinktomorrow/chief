<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class UniqueUrlSlugRule implements Rule
{
    /** @var string */
    private $model;

    /** @var Model */
    private $ignoredModel;

    private $failedDetails = [];

    public function __construct(Visitable $model, ?Model $ignoredModel = null)
    {
        $this->model = $model;
        $this->ignoredModel = $ignoredModel;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $slugs
     * @return bool
     */
    public function passes($attribute, $slugs)
    {
        foreach ($slugs as $locale => $slug) {
            $slug = $slug ? BaseUrlSegment::prepend($this->model, $slug, $locale) : null;

            if (UrlRecord::existsIgnoringRedirects($slug, $locale, $this->ignoredModel)) {
                session()->flash('unique_url_slug_validation', [
                    'locale' => $locale,
                    'slug' => $slug,
                    'ignoredModel' => $this->ignoredModel,
                ]);

                $this->failedDetails['slug'] = $slug;
                $this->failedDetails['locale'] = $locale;

                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'De \''.$this->failedDetails['slug'].'\' link wordt in het '.$this->failedDetails['locale'].' al door een andere pagina gebruikt.';
    }
}
