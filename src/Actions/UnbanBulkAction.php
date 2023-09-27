<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class UnbanBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'unban_bulk';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-bandel::translations.bulk-unban'));

        $this->successNotificationTitle(__('filament-bandel::translations.bulk-unban-success'));

        $this->color('primary');

        $this->icon('heroicon-o-lock-closed');

        $this->action(function () {
            $this->process(function (array $data, Collection $records) {
                $records->filter(fn ($r) => $r->banned_at)->each->unban();
            });

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}
