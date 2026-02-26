<?php

declare(strict_types=1);

namespace App\Service;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class LogFormatterService implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        return json_encode([
                'level' => $record->level->getName(),
                'message' => $record->message,
                'context' => $record->context,
                'datetime' => $record->datetime
                    ->format('Y-m-d H:i:s'),
            ]) . PHP_EOL;
    }

    public function formatBatch(array $records): string
    {
        return implode('', array_map([$this, 'format'], $records));
    }
}
