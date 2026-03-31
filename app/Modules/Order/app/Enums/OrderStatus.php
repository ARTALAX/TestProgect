<?php

namespace Modules\Order\Enums;

enum OrderStatus: string
{
    case CREATED = 'created';
    case PAID = 'paid';
    case IN_PROGRESS = 'in_progress';
    case DELIVERING = 'delivering';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
