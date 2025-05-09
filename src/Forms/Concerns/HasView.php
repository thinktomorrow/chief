<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasView
{
    protected string $view;

    protected string $previewView;

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function previewView(string $previewView): static
    {
        $this->previewView = $previewView;

        return $this;
    }

    public function getPreviewView(): string
    {
        return $this->previewView;
    }
}
