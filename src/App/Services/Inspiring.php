<?php

namespace LaravelEnso\Core\App\Services;

use Illuminate\Support\Collection;

class Inspiring
{
    public static function quote(): string
    {
        return (new Collection(config('enso.inspiring.quotes')))->random();
    }
}
