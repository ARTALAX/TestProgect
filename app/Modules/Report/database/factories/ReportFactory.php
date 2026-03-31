<?php

namespace Modules\Report\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Report\Enums\StatusReportEnum;
use Modules\Report\Models\Report;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement([StatusReportEnum::cases()]),
            'file_path' => null, // пустой по умолчанию, заполняется после генерации
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Состояние для завершённого отчёта с файлом
     */
    public function completed(?string $filePath = null): self
    {
        return $this->state(state: function () use ($filePath) {
            return [
                'status' => StatusReportEnum::COMPLETED,
                'file_path' => $filePath ?? "reports/report_{$this->faker->unique()->numberBetween(int1: 1, int2: 1000)}.jsonl",
            ];
        });
    }
}
