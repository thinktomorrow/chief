<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\ValidationRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Thinktomorrow\Chief\Managers\Register\Registry;
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

            // All this effort to get the other model reference in the error message
            $other = UrlRecord::findBySlug($slug, $locale);
            $otherModel = $other->model;
            $ownerResource = app(Registry::class)->findResourceByModel($otherModel::class);
            $otherAdminUrl = app(Registry::class)->findManagerByModel($otherModel::class)->route('edit', $otherModel);
            $otherModelTitle = $ownerResource->getPageTitle($otherModel);

            $fail('De \''.$slug.'\' link wordt al door <a class="link underline" href="'.$otherAdminUrl.'" target="_blank">'.$otherModelTitle.'</a> gebruikt.');
        }
    }
}
