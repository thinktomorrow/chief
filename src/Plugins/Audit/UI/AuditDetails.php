<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleHistory;

final class AuditDetails extends Component
{
    public bool $isOpen = false;

    #[Locked]
    public ?string $kind = null;

    #[Locked]
    public ?string $eventId = null;

    #[Locked]
    public ?string $modelId = null;

    #[On('audit:show-detail')]
    public function open(string $kind, string $eventId, ?string $modelId = null): void
    {
        abort_unless(in_array($kind, ['event', 'model', 'rich'], true), 404);
        abort_if($kind === 'model' && $modelId === null, 404);

        $this->kind = $kind;
        $this->eventId = $eventId;
        $this->modelId = $modelId;
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->kind = null;
        $this->eventId = null;
        $this->modelId = null;
    }

    public function render(VisibleHistory $history): View
    {
        $data = [];

        if ($this->isOpen) {
            $data = match ($this->kind) {
                'event' => $this->eventData($history),
                'model' => ['model' => $history->detail($this->eventId, $this->modelId)],
                'rich' => $this->richData($history),
            };
        }

        return view('chief-audit::audit-details', $data + ['kind' => $this->kind]);
    }

    private function eventData(VisibleHistory $history): array
    {
        $event = $history->eventDetail($this->eventId);

        return [
            'event' => $event,
            'history' => $history,
            'timezone' => $history->timezone(),
            'missingMailPreview' => $history->missingMailPreview($event),
        ];
    }

    private function richData(VisibleHistory $history): array
    {
        $pieces = $history->richData($this->eventId);

        foreach ($pieces as $piece) {
            if ($piece->type === 'reference' && $piece->status === 'available' && ! Storage::disk($piece->disk)->exists($piece->path)) {
                $piece->status = 'unavailable';
                $piece->metadata = [];
            }
        }

        return ['eventId' => $this->eventId, 'pieces' => $pieces];
    }
}
