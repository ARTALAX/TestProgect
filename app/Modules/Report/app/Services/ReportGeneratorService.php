<?php

namespace Modules\Report\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportGeneratorService
{
    /**
     * @throws \JsonException
     */
    public function generate(int $reportId, Carbon $start, Carbon $end): string
    {
        $filename = "reports/report_{$reportId}.jsonl";
        $tmpPath = storage_path(path: "app/tmp_report_{$reportId}.jsonl");
        $handle = fopen(filename: $tmpPath, mode: 'wb');
        DB::table('orders')
            ->select(columns: [
                'orders.id as order_id',
                'orders.created_at',
                'users.id as user_id',
                'users.name as user_name',
                'order_items.quantity',
                'products.id as product_id',
                'products.name as product_name',
            ])
            ->join(table: 'users', first: 'users.id', operator: '=', second: 'orders.user_id')
            ->join(table: 'order_items', first: 'order_items.order_id', operator: '=', second: 'orders.id') // <--- правильно orders.id
            ->join(table: 'products', first: 'products.id', operator: '=', second: 'order_items.product_id')
            ->whereBetween(column: 'orders.created_at', values: [$start, $end])
            ->orderBy(column: 'orders.id')
            ->chunk(count: 1000, callback: function ($rows) use ($handle): void {
                foreach ($rows as $row) {
                    fwrite(stream: $handle, data: json_encode(value: $row, flags: JSON_THROW_ON_ERROR)."\n");
                }
            })
        ;
        fclose(stream: $handle);
        Storage::disk('minio')->put($filename, fopen(filename: $tmpPath, mode: 'rb'));
        unlink(filename: $tmpPath);

        return $filename;
    }
}
