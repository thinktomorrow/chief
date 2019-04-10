<?php


namespace Thinktomorrow\Chief\Management;

use Thinktomorrow\Chief\Concerns\ProvidesUrl\ProvidesUrl;

trait ManagesPreviews
{
    public function previewUrl(): string
    {
        if( ! $this->model instanceof ProvidesUrl){
            throw new \Exception('Managed model ' . get_class($this->model) . ' should implement ' . ProvidesUrl::class);
        }

        return $this->model->previewUrl();
    }
}
