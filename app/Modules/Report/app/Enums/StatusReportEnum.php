<?php

namespace Modules\Report\Enums;

enum StatusReportEnum: string
{
    case PENDING = 'pending';

    case COMPLETED = 'completed';

    case FAILED = 'failed';
}
