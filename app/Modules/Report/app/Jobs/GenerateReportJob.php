<?php

namespace Modules\Report\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Modules\Report\Models\Report;
use Modules\Report\Services\ReportGeneratorService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    protected Carbon $periodStart;
    protected int $reportId;
    protected Carbon $periodEnd;

    public function __construct(int $reportId, Carbon $periodStart, Carbon $periodEnd)
    {
        $this->reportId = $reportId;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

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
            'status' => 'completed',
            'file_path' => $filename,
        ]);

        // 4. Публикуем событие в RabbitMQ
        $connection = new AMQPStreamConnection(
            host: config(key: 'queue.connections.rabbitmq.hosts.0.host'),
            port: config(key: 'queue.connections.rabbitmq.hosts.0.port'),
            user: config(key: 'queue.connections.rabbitmq.hosts.0.user'),
            password: config(key: 'queue.connections.rabbitmq.hosts.0.password'),
            vhost: config(key: 'queue.connections.rabbitmq.hosts.0.vhost')
        );

        $channel = $connection->channel();
        $msg = new AMQPMessage(body: json_encode(value: [
            'report_id' => $report->id,
            'file' => $filename,
            'status' => 'completed',
        ], flags: JSON_THROW_ON_ERROR));
        $channel->basic_publish(msg: $msg, exchange: 'reports', routing_key: 'reports.completed');
        $channel->close();
        $connection->close();
    }
}
