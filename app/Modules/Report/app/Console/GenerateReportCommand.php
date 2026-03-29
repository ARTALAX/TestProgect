<?php

namespace Modules\Report\Console;

use Illuminate\Console\Command;
use Modules\Report\Services\ReportService;

class GenerateReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'report:generate';

    protected $description = 'Generate daily report';

    public function handle(ReportService $service): void
    {
        $start = now()->subDay()->startOfDay();
        $end = now()->subDay()->endOfDay();

        $report = $service->createAndDispatch(periodStart: $start, periodEnd: $end);

        $this->info(string: "Report queued: {$report->id}");
    }
}
