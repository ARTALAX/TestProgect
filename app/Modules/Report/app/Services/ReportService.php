<?php

namespace Modules\Report\Services;

use Illuminate\Support\Carbon;
use Modules\Report\Enums\StatusReportEnum;
use Modules\Report\Jobs\GenerateReportJob;
use Modules\Report\Models\Report;

class ReportService
{
    public function createAndDispatch(Carbon $periodStart, Carbon $periodEnd): Report
    {
        $report = Report::create([
            'status' => StatusReportEnum::PENDING,
        ]);
        GenerateReportJob::dispatch($report->id, $periodStart, $periodEnd);

        return $report;
    }
}
