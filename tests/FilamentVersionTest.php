<?php

use Widiu7omo\FilamentBandel\Support\FilamentVersion;

it('detects the installed filament major consistently', function () {
    // Ground truth per environment: the Schemas layer only exists on v4+.
    // Across the CI matrix this flips, exercising both detection branches.
    $schemasLayerExists = class_exists(\Filament\Schemas\Schema::class);

    expect(FilamentVersion::isV4OrNewer())->toBe($schemasLayerExists)
        ->and(FilamentVersion::isV3())->toBe(! $schemasLayerExists);
});
