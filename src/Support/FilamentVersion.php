<?php

namespace Widiu7omo\FilamentBandel\Support;

use Filament\Schemas\Schema;

final class FilamentVersion
{
    /**
     * Filament v4 unified every action under the Filament\Actions namespace and
     * introduced the Schemas layer (Filament\Schemas\Schema), which does not
     * exist in v3. Its presence is therefore a reliable marker for v4+.
     *
     * Filament v5 has no code-level changes over v4 apart from Livewire 4
     * support, so everything that runs on v4 runs on v5.
     */
    public static function isV4OrNewer(): bool
    {
        return class_exists(Schema::class);
    }

    public static function isV3(): bool
    {
        return ! self::isV4OrNewer();
    }
}
