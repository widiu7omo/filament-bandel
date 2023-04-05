<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Closure;
use Illuminate\Database\Eloquent\Collection;

class UnbanBulkAction extends BulkAction
{
    protected bool|Closure $shouldDeselectRecordsAfterCompletion = true;

    protected string|Closure|null $icon = 'heroicon-o-lock-open';

    protected function setUp(): void
    {
        $this->action($this->handle(...));
    }

    protected function handle(Collection $records, array $data): void
    {
        $records->each->unban();
        Notification::make('unbanned_models')->success()->title(trans('filament-bandel::translations.bulk-unban-success'))->send();
    }
}
