<?php

namespace Widiu7omo\FilamentBandel\Actions;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Table;

class BanAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'ban';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->successNotificationTitle(__('filament-bandel::translations.single-ban-success'));

        $this->color('primary');

        $this->icon('heroicon-o-lock-closed');

        $this->form(function (Model $record){
            return $this->getFormSchema();
        });

        $this->action(function (): void {
            $this->process(function (array $data, Model $record, Table $table) {
                $record->ban([
                    'comment' => $data['comment'],
                    'expired_at' => $data['expired_at'],
                ]);
            });

            $this->success();
        });
    }

    public function getFormSchema(): array
    {
        return [
            Textarea::make('comment')->rows(4),
            DateTimePicker::make('expired_at')->label(__('Expires At')),
        ];
    }
}
