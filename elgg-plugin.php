<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

return [
    'actions' => [
        'dropzonejs_api/upload' => [],
    ],
    'routes' => [],
    'widgets' => [],
    'views' => [
        'default' => [
            'dropzonejs_api.js' => __DIR__ . '/vendors/dropzonejs/dist/dropzone.js',
            'dropzonejs_api.css' => __DIR__ . '/vendors/dropzonejs/dist/dropzone.css',
        ],
    ],
    'upgrades' => [],
];
