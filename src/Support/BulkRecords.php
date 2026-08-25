<?php

namespace Widiu7omo\FilamentBandel\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class BulkRecords
{
    /**
     * Re-read the given records from the database so filtering decisions use
     * authoritative state instead of possibly stale in-memory models.
     *
     * Records are grouped by morph class and re-fetched by primary key.
     * The optional banned-at global scope from cybercog/laravel-ban is
     * bypassed so banned rows stay visible to the caller-side filters.
     */
    public static function fresh(Collection $records): Collection
    {
        $items = $records
            ->filter(fn ($record) => $record instanceof Model)
            ->groupBy(fn (Model $record) => $record->getMorphClass())
            ->flatMap(function (Collection $group, string $morphClass) {
                $prototype = $group->first();

                $query = $prototype->newQuery()->whereKey($group->modelKeys());

                if (method_exists($prototype, 'bootHasBannedAtScope')) {
                    $query->withBanned();
                }

                return $query->get();
            })
            ->values()
            ->all();

        return new Collection($items);
    }
}
