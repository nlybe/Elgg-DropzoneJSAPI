<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

$result = DropzoneJSOptions::handleUploads();

if (elgg_is_xhr()) {
	echo json_encode($result);
}
