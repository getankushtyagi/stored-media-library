<?php

return [
    'disk' => 'public',
    
    'default_conversions' => [
        'thumb' => [
            'width' => 150,
            'height' => 150,
            'quality' => 80
        ],
        'medium' => [
            'width' => 800,
            'height' => 600,
            'quality' => 90
        ]
    ],
    
    'allowed_mimetypes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ],
    
    'max_file_size' => 10 * 1024 * 1024, // 10MB
];