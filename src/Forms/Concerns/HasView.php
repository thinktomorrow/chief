<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasView
{
    protected string $view;

    protected string $previewView;

    //    protected bool $editInSidebar = false;
    //
    //    public function getEditInSidebar(): bool
    //    {
    //        return $this->editInSidebar;
    //    }

    public function getView(): string
    {
        return $this->view;
        //        return ($this->editInSidebar && isset($this->previewView))
        //            ? $this->previewView
        //            : $this->view;
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

    //    public function editInSidebar(?string $previewView = null): static
    //    {
    //        $this->editInSidebar = true;
    //
    //        if ($previewView) {
    //            $this->previewView($previewView);
    //        }
    //
    //        return $this;
    //    }
    //
    //    public function editInline(): static
    //    {
    //        $this->editInSidebar = false;
    //
    //        return $this;
    //    }
}
