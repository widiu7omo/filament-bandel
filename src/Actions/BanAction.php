<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Model;
use Widiu7omo\FilamentBandel\Forms\BanForm;
use Widiu7omo\FilamentBandel\Support\FilamentVersion;

/**
 * Ban a single record.
 *
 * Composition factory instead of an extended base class so a single branch
 * can serve Filament 3 (table actions live in Filament\Tables\Actions) and
 * Filament 4/5 (actions unified under Filament\Actions).
 */
final class BanAction
{
    public static function getDefaultName(): string
    {
        return 'ban';
    }

    /**
     * @return Action|TableAction
     */
    public static function make()
    {
        $action = self::baseAction()
            ->label(__('filament-bandel::translations.ban'))
            ->color('primary')
            ->icon('heroicon-o-lock-closed')
            ->form(fn (): array => BanForm::schema())
            ->successNotificationTitle(__('filament-bandel::translations.single-ban-success'))
            ->action(function (array $data, Model $record): void {
                self::execute($record, $data);
            });

        /** @var Action|TableAction $action */
        return $action;
    }

    public static function execute(Model $record, array $data): void
    {
        $record->ban([
            'comment' => $data['comment'] ?? null,
            'expired_at' => $data['expired_at'] ?? null,
        ]);
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
