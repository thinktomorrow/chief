<?php

namespace Thinktomorrow\Chief\Urls\ValidationRules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Urls\UrlRecord;

class UniqueUrlSlugRule implements Rule
{
    /** @var Model */
    private $ignoredModel;

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
        foreach($slugs as $locale => $slug) {
            if ($locale == '_all_') {
                $locale = null;
            }

            if (UrlRecord::existsIgnoringRedirects($slug, $locale, $this->ignoredModel)) {
                session()->flash('unique_url_slug_validation', [
                    'locale'       => $locale,
                    'slug'         => $slug,
                    'ignoredModel' => $this->ignoredModel,
                ]);
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
        return 'De link wordt al door een andere pagina gebruikt.';
    }
}