<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum OrderStatusEnum: int
{
    use EnumHelpers;

    case Initialized = 1;
    case Pending = 2;
    case Completed = 3;
    case Sent = 4;
    case Closed = 5;
    case Canceled = 6;

    public function displayName(): string
    {
        return match ($this) {
            self::Initialized => 'Initialized',
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Sent => 'Sent',
            self::Closed => 'Closed',
            self::Canceled => 'Canceled',
        };
    }
}
