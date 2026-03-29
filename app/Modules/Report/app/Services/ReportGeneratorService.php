<?php

namespace Modules\Report\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Models\Order;

class ReportGeneratorService
{
    /**
     * @throws \JsonException
     */
    public function generate(int $reportId, Carbon $start, Carbon $end): string
    {
        $orders = Order::whereBetween('created_at', [$start, $end])->with(['user', 'items.product'])->get();

        $fileContent = '';

        foreach ($orders as $order) {
            $fileContent .= json_encode(value: $order->toArray(), flags: JSON_THROW_ON_ERROR)."\n";
        }

        $filename = "reports/report_{$reportId}.jsonl";

        Storage::disk('minio')->put($filename, $fileContent);

        return $filename;
    }
}
