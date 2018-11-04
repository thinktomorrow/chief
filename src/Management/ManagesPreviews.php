<?php


namespace Thinktomorrow\Chief\Management;

trait ManagesPreviews
{
    public function previewUrl(): string
    {
        $showRouteName = config('thinktomorrow.chief.routes.pages-show', 'pages.show');

        return route($showRouteName, $this->model->slug). '?preview-mode';
    }
}