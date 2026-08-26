<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Model;
use Widiu7omo\FilamentBandel\Support\FilamentVersion;

/**
 * Unban a single record.
 *
 * @see BanAction for the multi-version composition rationale.
 */
final class UnbanAction
{
    public static function getDefaultName(): string
    {
        return 'unban';
    }

    /**
     * @return Action|TableAction
     */
    public static function make()
    {
        $action = self::baseAction()
            ->label(__('filament-bandel::translations.unban'))
            ->color('primary')
            ->icon('heroicon-o-lock-open')
            ->successNotificationTitle(__('filament-bandel::translations.single-unban-success'))
            ->action(function (Model $record): void {
                self::execute($record);
            });

        /** @var Action|TableAction $action */
        return $action;
    }

    public static function execute(Model $record): void
    {
        $record->unban();
    }

    /**
     * @return Action|TableAction
     */
    private static function baseAction()
    {
        $actionClass = FilamentVersion::isV3() ? TableAction::class : Action::class;

        return $actionClass::make(self::getDefaultName());
    }
}
