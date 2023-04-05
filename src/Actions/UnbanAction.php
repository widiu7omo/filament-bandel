<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Closure;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class UnbanAction extends Action
{
    protected string|Closure|null $icon = "heroicon-o-lock-open";

    public static function make(?string $name = 'unbanned'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        $this->action($this->handle(...));
    }

    protected function handle(Model $record, array $data): void
    {
        $record->unban();
        Notification::make('unbanned-single-record')->success()->title(trans('filament-bandel::translations.single-unban-success'))->send();
    }
}
