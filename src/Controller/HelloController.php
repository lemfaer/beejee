<?php

namespace App\Controller;

use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;

class HelloController extends Controller
{
    public function greet(): Response
    {
        return $this->response(200, "hello");
    }
}
