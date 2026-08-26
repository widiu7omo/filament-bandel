<?php

use Illuminate\Database\Eloquent\Collection;
use Widiu7omo\FilamentBandel\Actions\BanAction;
use Widiu7omo\FilamentBandel\Actions\BanBulkAction;
use Widiu7omo\FilamentBandel\Actions\UnbanAction;
use Widiu7omo\FilamentBandel\Actions\UnbanBulkAction;
use Widiu7omo\FilamentBandel\Support\FilamentVersion;
use Widiu7omo\FilamentBandel\Tests\Fixtures\User;

function expectedActionClasses(): array
{
    if (FilamentVersion::isV3()) {
        return [
            \Filament\Tables\Actions\Action::class,
            \Filament\Tables\Actions\BulkAction::class,
        ];
    }

    return [
        \Filament\Actions\Action::class,
        \Filament\Actions\BulkAction::class,
    ];
}

it('builds every action against the installed filament major', function () {
    [$single, $bulk] = expectedActionClasses();

    expect(BanAction::make())->toBeInstanceOf($single)
        ->and(UnbanAction::make())->toBeInstanceOf($single)
        ->and(BanBulkAction::make())->toBeInstanceOf($bulk)
        ->and(UnbanBulkAction::make())->toBeInstanceOf($bulk);
});

it('keeps the historical action names', function () {
    expect(BanAction::make()->getName())->toBe('ban')
        ->and(UnbanAction::make()->getName())->toBe('unban')
        ->and(BanBulkAction::make()->getName())->toBe('ban_bulk')
        ->and(UnbanBulkAction::make()->getName())->toBe('unban_bulk');
});

it('bans a record with comment and expiry', function () {
    $user = User::create(['name' => 'Danar']);
    $expiresAt = now()->addDays(7);

    BanAction::execute($user, [
        'comment' => 'Spamming',
        'expired_at' => $expiresAt->toDateTimeString(),
    ]);

    expect($user->fresh()->isBanned())->toBeTrue();

    $ban = $user->fresh()->bans()->first();
    expect($ban->comment)->toBe('Spamming')
        ->and($ban->expired_at->format('Y-m-d H:i:s'))->toBe($expiresAt->format('Y-m-d H:i:s'));
});

it('unbans a banned record', function () {
    $user = User::create(['name' => 'Danar']);

    BanAction::execute($user, []);
    expect($user->fresh()->isBanned())->toBeTrue();

    UnbanAction::execute($user);
    expect($user->fresh()->isBanned())->toBeFalse();
});

it('bans every unbanned record during bulk ban', function () {
    $a = User::create(['name' => 'A']);
    $b = User::create(['name' => 'B']);

    BanBulkAction::execute(new Collection([$a, $b]), [
        'comment' => 'Raid',
        'expired_at' => null,
    ]);

    expect($a->fresh()->isBanned())->toBeTrue()
        ->and($b->fresh()->isBanned())->toBeTrue()
        ->and($a->fresh()->bans()->first()->comment)->toBe('Raid');
});

it('skips already banned records so bulk ban does not duplicate bans', function () {
    $a = User::create(['name' => 'A']);
    $b = User::create(['name' => 'B']);
    $records = new Collection([$a, $b]);

    BanBulkAction::execute($records, ['comment' => null, 'expired_at' => null]);
    BanBulkAction::execute($records, ['comment' => null, 'expired_at' => null]);

    expect($a->fresh()->bans()->count())->toBe(1)
        ->and($b->fresh()->bans()->count())->toBe(1);
});

it('unbans only banned records during bulk unban', function () {
    $a = User::create(['name' => 'A']); // never banned
    $b = User::create(['name' => 'B']);
    BanAction::execute($b, []);

    UnbanBulkAction::execute(new Collection([$a, $b]));

    expect($a->fresh()->isBanned())->toBeFalse()
        ->and($a->fresh()->bans()->count())->toBe(0)
        ->and($b->fresh()->isBanned())->toBeFalse();
});
