<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Actions\BulkAction;
use Filament\Tables\Actions\BulkAction as TableBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Widiu7omo\FilamentBandel\Forms\BanForm;
use Widiu7omo\FilamentBandel\Support\BanState;
use Widiu7omo\FilamentBandel\Support\BulkRecords;
use Widiu7omo\FilamentBandel\Support\FilamentVersion;

/**
 * Ban multiple records at once.
 *
 * @see BanAction for the multi-version composition rationale.
 */
final class BanBulkAction
{
    public static function getDefaultName(): string
    {
        return 'ban_bulk';
    }

    /**
     * @return BulkAction|TableBulkAction
     */
    public static function make()
    {
        $action = self::baseAction()
            ->label(__('filament-bandel::translations.bulk-ban'))
            ->color('primary')
            ->icon('heroicon-o-lock-closed')
            ->form(fn (): array => BanForm::schema())
            ->successNotificationTitle(__('filament-bandel::translations.bulk-ban-success'))
            ->action(function (array $data, Collection $records): void {
                self::execute($records, $data);
            })
            ->deselectRecordsAfterCompletion();

        /** @var BulkAction|TableBulkAction $action */
        return $action;
    }

    public static function execute(Collection $records, array $data): void
    {
        DB::transaction(function () use ($records, $data): void {
            BulkRecords::fresh($records)
                ->filter(fn (Model $record) => ! BanState::isBanned($record))
                ->each(fn (Model $record) => $record->ban([
                    'comment' => $data['comment'] ?? null,
                    'expired_at' => $data['expired_at'] ?? null,
                ]));
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
