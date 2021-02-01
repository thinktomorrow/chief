<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Translatable;

use Illuminate\Http\Request;

trait TranslatableController
{
    use TranslatableCommand;

    /**
     * @param Request $request
     */
    protected function validateTranslationRequest(Request $request, array $rules, array $attributes = [], array $messages = [])
    {
        $translationrules = [];
        $translationattributes = [];
        $translationmessages = [];

        foreach ($request->input('trans') as $locale => $trans) {
            if ($this->isCompletelyEmpty($rules, $trans)) {
                continue;
            }

            foreach ($rules as $key => $rule) {
                $translationrules['trans.' . $locale . '.' . $key] = $rule;
            }

            foreach ($attributes as $key => $attribute) {
                $translationattributes['trans.' . $locale . '.' . $key] = $attribute;
            }

            foreach ($messages as $key => $message) {
                $translationmessages['trans.' . $locale . '.' . $key] = $message;
            }
        }

        $this->validate($request, $translationrules, $translationattributes, $translationmessages);
    }
}
