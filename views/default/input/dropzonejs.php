<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

elgg_require_js('dropzonejs_api/dropzone'); 
elgg_require_css('dropzonejs_api.css');

echo elgg_view_field([
    'id' => 'file_upload',
    '#type' => 'file',
    'name' => 'file',
    'class' => 'hidden',
]);
 
?>

<div id="dropzone_upload" class="dz-message needsclick">   
	<?= elgg_view_icon('cloud-upload') ?> 
	<p>Drop your files here</p>
	<p class="note needsclick">or click to select them from your computer</p>
</div>

<?php
echo elgg_view_field([
    'id' => 'subtype',
    '#type' => 'hidden',
	'name' => 'subtype',
	'value' => elgg_extract('subtype', $vars), 
]);
