<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API for managing tasks.',
    title: 'Tasks API'
)]
abstract class Controller
{
    //
}
