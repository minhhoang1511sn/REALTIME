<?php

return [
    'paths' => ['api/*', 'broadcasting/auth'],
    'allowed_origins' => ['http://127.0.0.1:8000', 'http://localhost:5173'], // Địa chỉ mà bạn đang phát triển
    'supports_credentials' => true,
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
];
