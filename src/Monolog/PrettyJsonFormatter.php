<?php

declare(strict_types=1);

namespace App\Monolog;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class PrettyJsonFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        return json_encode([
                'message' => $record->message,
                'context' => $record->context,
                'level' => $record->level->value,
                'level_name' => $record->level->getName(),
                'channel' => $record->channel,
                'datetime' => $record->datetime->format('c'),
                'extra' => $record->extra,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }

    public function formatBatch(array $records): string
    {
        return implode('', array_map([$this, 'format'], $records));
    }
}
