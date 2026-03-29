<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Models\Report;
use Modules\Report\Services\ReportService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function store(Request $request, ReportService $service): JsonResponse
    {
        $periodStart = Carbon::parse(time: $request->input(key: 'start', default: now()->startOfDay()));
        $periodEnd = Carbon::parse(time: $request->input(key: 'end', default: now()->endOfDay()));

        $report = $service->createAndDispatch(periodStart: $periodStart, periodEnd: $periodEnd);

        return response()->json(['message' => 'Job dispatched', 'report_id' => $report->id]);
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
