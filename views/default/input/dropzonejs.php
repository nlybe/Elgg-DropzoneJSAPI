<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

elgg_require_js('dropzonejs_api/dropzone'); 
elgg_load_css('dropzonejs_css');

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

<?php /*
<div class="dz-preview dz-file-preview">
  <div class="dz-details">
    <div class="dz-filename"><span data-dz-name></span></div>
    <div class="dz-size" data-dz-size></div>
	<img data-dz-thumbnail />
	<img data-dz-remove />
  </div>
  <!-- <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div> -->
  <div class="dz-success-mark"><span>✔</span></div>
  <div class="dz-error-mark"><span>✘</span></div>
  <div class="dz-error-message"><span data-dz-errormessage></span></div>
</div> 
*/ ?>

<?php
echo elgg_view_field([
    'id' => 'subtype',
    '#type' => 'hidden',
	'name' => 'subtype',
	'value' => elgg_extract('subtype', $vars), 
]);
