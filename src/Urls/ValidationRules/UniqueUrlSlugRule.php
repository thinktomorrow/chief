<?php

namespace Thinktomorrow\Chief\Urls\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Urls\UrlRecord;

class UniqueUrlSlugRule implements Rule
{
    /** @var Model */
    private $ignoredModel;

    private $failedDetails = [];

    public function __construct(Model $ignoredModel = null)
    {
        $this->ignoredModel = $ignoredModel;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  bool $slugs
     * @return bool
     */
    public function passes($attribute, $slugs)
    {
        foreach ($slugs as $locale => $slug) {
            if (UrlRecord::existsIgnoringRedirects($slug, $locale, $this->ignoredModel)) {
                session()->flash('unique_url_slug_validation', [
                    'locale'       => $locale,
                    'slug'         => $slug,
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
