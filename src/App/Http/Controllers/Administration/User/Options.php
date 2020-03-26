<?php

namespace LaravelEnso\Core\App\Http\Controllers\Administration\User;

use Illuminate\Routing\Controller;
use LaravelEnso\Core\App\Models\User;
use LaravelEnso\Select\App\Traits\OptionsBuilder;

class Options extends Controller
{
    use OptionsBuilder;

    protected $queryAttributes = ['email', 'person.name', 'person.appellative'];

    public function query()
    {
        return User::active()
            ->with(['person:id,appellative,name', 'avatar:id,user_id']);
    }
}
