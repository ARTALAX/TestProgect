<?php

namespace Modules\Report\Listeners;

use Modules\Report\Enums\StatusReportEnum;
use Modules\Report\Events\ReportCompleted;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublishReportCompleted
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @throws \JsonException
     * @throws \Exception
     */
    public function handle(ReportCompleted $event): void
    {
        $report = $event->report;

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
            'file' => $report->file_path,
            'status' => StatusReportEnum::COMPLETED,
        ], flags: JSON_THROW_ON_ERROR));
        $channel->basic_publish(msg: $msg, exchange: 'reports', routing_key: 'reports.completed');
        $channel->close();
        $connection->close();
    }
}
