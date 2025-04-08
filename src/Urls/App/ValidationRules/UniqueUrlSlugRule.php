<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\ValidationRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UniqueUrlSlugRule implements ValidationRule
{
    private Visitable $model;

    private ?Visitable $ignoredModel;

    public function __construct(Visitable $model, ?Visitable $ignoredModel = null)
    {
        $this->model = $model;
        $this->ignoredModel = $ignoredModel;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Attribute is form.locale.slug so extract the locale from the attribute
        $locale = explode('.', $attribute)[1];

        $slug = $value ? BaseUrlSegment::prepend($this->model, $value, $locale) : null;

        if (UrlRecord::existsIgnoringRedirects($slug, $locale, $this->ignoredModel)) {
            session()->flash('unique_url_slug_validation', [
                'locale' => $locale,
                'slug' => $slug,
                'ignoredModel' => $this->ignoredModel,
            ]);

            $fail('De \''.$value.'\' link wordt al door een andere pagina gebruikt.');
        }
    }
}
