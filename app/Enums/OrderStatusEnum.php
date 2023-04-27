<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum OrderStatusEnum: int
{
    use EnumHelpers;


    case Initialize = 1;
    case Pending = 2;
    case Completed = 3;
    case Sent = 4;
    case Closed = 5;
    case Canceled = 6;
}
