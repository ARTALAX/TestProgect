<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Models\Order;
use Modules\Report\Enums\StatusReportEnum;
use Modules\Report\Events\ReportCompleted;
use Modules\Report\Jobs\GenerateReportJob;
use Modules\Report\Models\Report;
use Modules\Report\Services\ReportGeneratorService;
use Modules\User\Models\User;
use Tests\TestCase;

uses(RefreshDatabase::class, TestCase::class);

beforeEach(closure: function (): void {
    // Fake MinIO storage
    Storage::fake(disk: 'minio');

    // Fake Queue
    Queue::fake();
    Event::fake();
    // Создаём тестового пользователя
    $this->user = User::factory()->create();

    // Создаём тестовые заказы
    Order::factory()->count(count: 3)->create(attributes: [
        'user_id' => $this->user->id,
        'created_at' => now(),
    ]);
});

it(description: 'can create report via API and dispatch job', closure: function (): void {
    $response = $this->postJson('/api/reports', [
        'start' => now()->startOfDay()->toDateTimeString(),
        'end' => now()->endOfDay()->toDateTimeString(),
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'report_id'])
    ;

    $reportId = $response->json('report_id');

    // Проверяем, что Job был поставлен в очередь
    Queue::assertPushed(GenerateReportJob::class);
});

it(description: 'can show report status', closure: function (): void {
    $report = Report::factory()->create(attributes: [
        'status' => StatusReportEnum::PENDING->value,
    ]);

    $response = $this->getJson("/api/reports/{$report->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $report->id,
            'status' => StatusReportEnum::PENDING->value,
        ])
    ;
});

it(description: 'can download completed report file', closure: function (): void {
    // Создаём report с файлом
    $report = Report::factory()->create(attributes: [
        'status' => StatusReportEnum::COMPLETED->value,
        'file_path' => 'reports/report_1.jsonl',
    ]);

    Storage::disk('minio')->put($report->file_path, 'test content');

    $response = $this->get("/api/reports/{$report->id}/download");

    $response->assertStatus(200);
    $response->assertHeader('content-disposition');
});

it(description: 'GenerateReportJob creates file and updates report', closure: function (): void {
    $report = Report::factory()->create(attributes: ['status' => StatusReportEnum::PENDING->value]);

    // Создаём генератор вручную
    $generator = new ReportGeneratorService();

    $job = new GenerateReportJob(reportId: $report->id, periodStart: now()->startOfDay(), periodEnd: now()->endOfDay());
    $job->handle(generator: $generator);
    $report->refresh();

    // Проверяем, что статус обновился
    expect(value: $report->status)->toBe(StatusReportEnum::COMPLETED->value)
        ->and(Storage::disk('minio')->exists($report->file_path))->toBeTrue()
    ;

    // Проверяем, что файл создан

    // Проверяем, что событие было вызвано
    Event::assertDispatched(ReportCompleted::class, fn ($e) => $e->report->id === $report->id);
});
