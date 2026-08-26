<?php

namespace Widiu7omo\FilamentBandel\Support;

use Illuminate\Database\Eloquent\Model;

final class BanState
{
    /**
     * Whether the given record currently counts as banned.
     *
     * Prefers the Bannable trait helper from cybercog/laravel-ban; falls back
     * to the cached banned_at column for models that expose it without
     * using the trait.
     */
    public static function isBanned(Model $record): bool
    {
        if (method_exists($record, 'isBanned')) {
            return (bool) $record->isBanned();
        }

        return filled($record->banned_at ?? null);
    }
}
