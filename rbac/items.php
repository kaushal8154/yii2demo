<?php

return [
    'viewFile' => [
        'type' => 2,
        'description' => 'View own files',
    ],
    'addFile' => [
        'type' => 2,
        'description' => 'Add New file',
    ],
    'editFile' => [
        'type' => 2,
        'description' => 'Update file',
    ],
    'downloadFile' => [
        'type' => 2,
        'description' => 'Download file',
    ],
    'employee' => [
        'type' => 1,
        'children' => [
            'viewFile',
            'downloadFile',
        ],
    ],
    'manager' => [
        'type' => 1,
        'children' => [
            'addFile',
            'editFile',
        ],
    ],
];
