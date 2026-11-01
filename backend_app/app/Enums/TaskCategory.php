<?php

namespace App\Enums;

enum TaskCategory: string
{
    case WORK = 'work';
    case HOME = 'home';
    case PERSONAL = 'personal';
    case OTHER = 'other';
}
