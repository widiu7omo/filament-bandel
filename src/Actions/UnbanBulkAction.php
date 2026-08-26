<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Actions\BulkAction;
use Filament\Tables\Actions\BulkAction as TableBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Widiu7omo\FilamentBandel\Support\BanState;
use Widiu7omo\FilamentBandel\Support\BulkRecords;
use Widiu7omo\FilamentBandel\Support\FilamentVersion;

/**
 * Unban multiple records at once.
 *
 * @see BanAction for the multi-version composition rationale.
 */
final class UnbanBulkAction
{
    public static function getDefaultName(): string
    {
        return 'unban_bulk';
    }

    /**
     * @return BulkAction|TableBulkAction
     */
    public static function make()
    {
        $action = self::baseAction()
            ->label(__('filament-bandel::translations.bulk-unban'))
            ->color('primary')
            ->icon('heroicon-o-lock-open')
            ->successNotificationTitle(__('filament-bandel::translations.bulk-unban-success'))
            ->action(function (Collection $records): void {
                self::execute($records);
            })
            ->deselectRecordsAfterCompletion();

        /** @var BulkAction|TableBulkAction $action */
        return $action;
    }

    public static function execute(Collection $records): void
    {
        DB::transaction(function () use ($records): void {
            BulkRecords::fresh($records)
                ->filter(fn (Model $record) => BanState::isBanned($record))
                ->each(fn (Model $record) => $record->unban());
        });
    }

    /**
     * @return BulkAction|TableBulkAction
     */
    private static function baseAction()
    {
        $bulkClass = FilamentVersion::isV3() ? TableBulkAction::class : BulkAction::class;

        return $bulkClass::make(self::getDefaultName());
    }
}
