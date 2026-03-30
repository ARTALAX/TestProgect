<?php

namespace Modules\Report\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Modules\Report\Enums\StatusReportEnum;
use Modules\Report\Events\ReportCompleted;
use Modules\Report\Models\Report;
use Modules\Report\Services\ReportGeneratorService;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected int $reportId, protected Carbon $periodStart, protected Carbon $periodEnd) {}

    /**
     * Execute the job.
     *
     * @throws \JsonException
     * @throws \Exception
     */
    public function handle(ReportGeneratorService $generator): void
    {
        $filename = $generator->generate(
            reportId: $this->reportId,
            start: $this->periodStart,
            end: $this->periodEnd
        );

        $report = Report::query()->findOrFail($this->reportId);

        $report->update(attributes: [
            'status' => StatusReportEnum::COMPLETED,
            'file_path' => $filename,
        ]);

        event(new ReportCompleted(report: $report));
    }
}
