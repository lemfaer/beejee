<?php

use App\Controller as c;

return [
    [ "GET", "~^$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/sort/([\w-]+)$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/sort/([\w-]+)/page/([1-9][0-9]*)$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/new$~", [ c\TaskController::class, "new" ] ],
    [ "POST", "~^/new$~", [ c\TaskController::class, "formSubmit" ] ],
    [ "GET", "~^/login$~", [ c\AuthController::class, "login" ] ],
    [ "GET", "~^/logout$~", [ c\AuthController::class, "logout" ] ],
    [ "POST", "~^/login$~", [ c\AuthController::class, "loginSubmit" ] ],
    [ "GET", "~^/task/(\d+)$~", [ c\TaskController::class, "single" ] ],
    [ "GET", "~^/hello$~", [ c\HelloController::class, "greet" ] ],
];
