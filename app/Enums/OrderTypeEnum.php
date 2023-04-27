<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum OrderTypeEnum: int
{
    use EnumHelpers;

    case Product = 1;
    case Service = 2;
}
