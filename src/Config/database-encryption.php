<?php

return [
    'enable_encryption' => true,
    'encryption_method' => 'aes-128-ecb',
    'encryption_key'    => env('APP_KEY', null),
];