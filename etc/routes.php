<?php

use App\Controller as c;

return [
    [ "GET", "~^$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/sort/([\w-]+)$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/sort/([\w-]+)/page/([1-9][0-9]*)$~", [ c\TaskController::class, "list" ] ],
    [ "GET", "~^/task/(\d+)$~", [ c\TaskController::class, "single" ] ],
    [ "GET", "~^/hello$~", [ c\HelloController::class, "greet" ] ],
];
