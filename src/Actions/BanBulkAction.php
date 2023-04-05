<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Closure;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;

class BanBulkAction extends BulkAction
{

    protected bool|Closure $shouldDeselectRecordsAfterCompletion = true;

    protected string|Closure|null $icon = 'heroicon-o-lock-closed';

    protected function setUp(): void
    {
        $this->modalWidth = 'sm';
        $this->action($this->handle(...));
    }

    protected function handle(Collection $records, array $data): void
    {
        $records->each->ban([
            'comment' => $data['comment'],
            'expired_at' => $data['expired_at'],
        ]);
        Notification::make('unbanned_models')->success()->title(trans('filament-bandel::translations.bulk-ban-success'))->send();
    }

    public function getFormSchema(): array
    {
        return [
            Textarea::make('comment')->rows(4),
            DateTimePicker::make('expired_at')->label(__('Expires At')),
        ];
    }


}
