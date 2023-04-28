<?php

namespace App\Actions\FactoryActions;

use App\Models\Option;

class OptionFactory implements ResourceFactory
{

    function createResource(array $data): Option
    {
        return Option::create($data);
    }
}
