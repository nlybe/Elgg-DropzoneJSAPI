<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */
 
elgg_register_event_handler('init', 'system', 'dropzonejs_api_init');

/**
 * dropzonejs_api plugin initialization functions.
 */
function dropzonejs_api_init() {

    // register extra css
    elgg_extend_view('elgg.css', 'dropzonejs_api/css.css');
    elgg_register_css('dropzonejs_css', elgg_get_simplecache_url('dropzonejs_api.css'));

    // dropzone.js library
    elgg_define_js('dropzonejs_api', [
        'deps' => [],
        'exports' => 'dropzonejs_api',
    ]);
    // use with require('dropzonejs_api');
}
