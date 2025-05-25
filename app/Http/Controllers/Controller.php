<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes\Info;
use OpenApi\Attributes\Server;

#[Info(
    version: 'TEST VERSION',
    description: "Get the API SQ Systems",
    title: "Api SQ Systems"
)]
#[Server(
    url: 'http://localhost'
)]

abstract class Controller
{
    //
}
