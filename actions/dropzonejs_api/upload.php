<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

use DropzonejsApi\DropzoneJSOptions;

$result = DropzoneJSOptions::handleUploads();

if (elgg_is_xhr()) {
	echo json_encode($result);
}
