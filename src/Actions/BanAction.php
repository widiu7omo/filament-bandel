<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class BanAction extends Action
{
    protected string|Closure|null $icon = "heroicon-o-lock-closed";

    public static function make(?string $name = 'banned'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        $this->modalWidth = 'sm';
        $this->action($this->handle(...));
    }

    protected function handle(Model $record, array $data)
    {
        $record->ban([
            'comment' => $data['comment'],
            'expired_at' => $data['expired_at'],
        ]);
        Notification::make('banned-single-record')->success()->title(trans('filament-bandel::translations.single-ban-success'))->send();
    }

    public function getFormSchema(): array
    {
        return [
            Textarea::make('comment')->rows(4),
            DateTimePicker::make('expired_at')->label(__('Expires At')),
        ];
    }
}
