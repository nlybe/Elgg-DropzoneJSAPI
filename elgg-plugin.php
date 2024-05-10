<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

use DropzonejsApi\Elgg\Bootstrap;

return [
    'plugin' => [
        'name' => 'DropzoneJS API',
		'version' => '5.4',
		'dependencies' => [],
	],	
    'bootstrap' => Bootstrap::class,
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
	'view_extensions' => [
		'elgg.css' => [
			'dropzonejs_api/css.css' => [],
		],
	],
];
