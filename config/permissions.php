<?php

return [
    [
        'name' => 'Request Quote',
        'flag' => 'request-quote.index',
    ],
    [
        'name' => 'Delete Request Quote',
        'flag' => 'request-quote.destroy',
        'parent_flag' => 'request-quote.index',
    ],
    [
        'name' => 'Request Quote Settings',
        'flag' => 'request-quote.settings',
        'parent_flag' => 'request-quote.index',
    ],
];