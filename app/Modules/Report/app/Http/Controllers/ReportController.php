<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Http\Requests\StoreReportRequest;
use Modules\Report\Models\Report;
use Modules\Report\Services\ReportService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request, ReportService $service): JsonResponse
    {
        $report = $service->createAndDispatch(periodStart: $request->getPeriodStart(), periodEnd: $request->getPeriodEnd());

        return response()->json(['message' => __(key: 'report::reports.job_dispatched'), 'report_id' => $report->id]);
    }

    public function show(int $id): JsonResponse
    {
        $report = Report::findOrFail($id);

        return response()->json([
            'id' => $report->id,
            'status' => $report->status,
            'created_at' => $report->created_at,
        ]);
    }

    public function download(int $id): StreamedResponse
    {
        $report = Report::findOrFail($id);

        return Storage::disk('minio')->download($report->file_path);
    }
}
