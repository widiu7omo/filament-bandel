<?php

namespace Widiu7omo\FilamentBandel\Forms;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;

final class BanForm
{
    /**
     * Shared form used by both the single-record ban action and the bulk ban
     * action. Component namespaces are stable across Filament 3, 4 and 5.
     */
    public static function schema(): array
    {
        return [
            Textarea::make('comment')
                ->label(__('filament-bandel::translations.comment'))
                ->rows(4)
                ->maxLength(65535),

            DateTimePicker::make('expired_at')
                ->label(__('filament-bandel::translations.expires_at'))
                ->minDate(now())
                ->nullable(),
        ];
    }
}
