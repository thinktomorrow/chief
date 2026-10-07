<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class HistoryIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_unless(Gate::allows('view-audit'), 403);

        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'search' => 'sometimes|nullable|string|max:190',
            'type' => ['sometimes', 'nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! is_string($value) && ! is_array($value) || is_string($value) && mb_strlen($value) > 190 || is_array($value) && count($value) > 20) {
                    $fail('Ongeldige typesleutel.');
                }
            }],
            'type.*' => 'string|max:190',
            'category' => 'sometimes|nullable|string|max:100',
            'outcome' => 'sometimes|nullable|string|max:100',
            'actor' => 'sometimes|nullable|string|max:190',
            'actor_type' => 'sometimes|nullable|string|max:20',
            'model_type' => 'sometimes|nullable|string|max:190',
            'model_id' => 'sometimes|nullable|string|max:190',
            'from' => 'sometimes|nullable|date_format:Y-m-d',
            'to' => 'sometimes|nullable|date_format:Y-m-d',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'show_all' => 'sometimes|boolean',
        ];
    }
}
