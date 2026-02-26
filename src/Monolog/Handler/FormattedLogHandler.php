<?php

declare(strict_types=1);

namespace App\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use App\Monolog\PrettyJsonFormatter;

class FormattedLogHandler extends StreamHandler
{
    public function __construct(string $stream)
    {
        parent::__construct($stream);

        $this->setFormatter(new PrettyJsonFormatter());
    }
}
