<?php

namespace App\Enums;

enum TaskStatus: string
{
    case DONE = 'done';
    case NOT_DONE = 'not_done';
}
