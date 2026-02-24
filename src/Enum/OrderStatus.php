<?php

namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case MODIFIED = 'modified';
    case FAILED = 'failed';
}
