<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FileUploadAssistant
{
    public function routesFileUploadAssistant(): array
    {
        return [
            ManagedRoute::post('asyncUploadFile', '{fieldkey}/asyncUploadFile/{id?}'),
        ];
    }

    public function canFileUploadAssistant(string $action, $model = null): bool
    {
        if (in_array($action, ['asyncUploadFile'])) {
            return true;
        }

        return false;
    }

    /**
     * Upload a file via the file field. Keep in mind
     * that here one file at a time is upload asynchronously.
     *
     * @param mixed      $fieldKey
     * @param null|mixed $id
     */
    public function asyncUploadFile(Request $request, $fieldKey, $id = null)
    {
        $uploadedFile = $request->file('file');
        $locale = $request->input('locale');

        $model = $id ? $this->fieldsModel($id) : new $this->managedModelClass();

        return app(Fields\Media\Application\AsyncFileUpload::class)->upload($model, $fieldKey, $uploadedFile, $locale);
    }

    abstract protected function fieldsModel($id);
}
