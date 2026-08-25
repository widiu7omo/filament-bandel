<?php

namespace Widiu7omo\FilamentBandel\Tests\Fixtures;

use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements BannableContract
{
    use Bannable;

    protected $fillable = ['name'];
}
