<?php

namespace Modules\Report\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Carbon;

class StoreReportRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ];
    }

    public function getPeriodStart(): Carbon
    {
        return Carbon::parse(
            time: $this->input(key: 'start', default: now()->startOfDay())
        );
    }

    public function getPeriodEnd(): Carbon
    {
        return Carbon::parse(
            time: $this->input(key: 'end', default: now()->endOfDay())
        );
    }
}
