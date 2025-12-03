<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasView
{
    protected string $view;

    protected string $previewView;

    protected array $viewData = [];

    protected array $previewViewData = [];

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view, array $viewData = []): static
    {
        $this->view = $view;
        $this->viewData = $viewData;

        return $this;
    }

    public function setPreviewView(string $previewView, array $viewData = []): static
    {
        $this->previewView = $previewView;
        $this->previewViewData = $viewData;

        return $this;
    }

    public function previewView(string $previewView, array $viewData = []): static
    {
        return $this->setPreviewView($previewView, $viewData);
    }

    public function getPreviewView(): string
    {
        return $this->previewView;
    }

    public function getViewData(): array
    {
        return $this->viewData;
    }

    public function getPreviewViewData(): array
    {
        return $this->previewViewData ?? $this->viewData;
    }
}
