<?php

namespace Modules\Report\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Report\Models\Report;

class ReportCompleted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    // Get the channels the event should be broadcast on.
}
