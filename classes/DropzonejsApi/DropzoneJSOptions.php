<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

namespace DropzonejsApi;
use ElggFile;

class DropzoneJSOptions {

    const PLUGIN_ID = 'egallery';    // current plugin ID

    /**
     * Check if gallery option is enabled for given type/subtype
     * 
     * @param ElggEntity $entity
     * @param string $cat: connection category, e.g. users
     * @return boolean
     */
    Public Static function isEntityTypeGalleryEnabled($sub) {
        $plugin = elgg_get_plugin_from_id(self::PLUGIN_ID);
        $param_name_entity = "egallery_{$sub}";
        if ($plugin->$param_name_entity) {
            return true;
        }
        
        return false;
    }   
    
	/**
	 * dropzone/upload action handler
	 * @return array
	 */
	Public Static function handleUploads() {
		$subtype = get_input('subtype');
		$uploads = self::saveUploadedFiles('file', [
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'container_guid' => get_input('container_guid') ? : ELGG_ENTITIES_ANY_VALUE,
			'subtype' => $subtype,
			'access_id' => ACCESS_PRIVATE,
			'origin' => get_input('origin', 'dropzone'),
		]);
		
		$output = [];
		foreach ($uploads as $upload) {
			$messages = [];
			$success = true;
			if ($upload->error) {
				$messages[] = $upload->error;
				$success = false;
				$guid = false;
			} 
			else {
				$file = $upload->file;
				$guid = $file->guid;
			}

			$file_output = [
				'messages' => $messages,
				'success' => $success,
				'guid' => $guid,
			];
			
			$output[] = elgg_trigger_event_results('upload:after', 'dropzonejs_api', [
				'upload' => $upload,
			], $file_output);
		}
		
		return $output;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 * @return UploadedFile[]
	 */
	protected Static function getUploadedFiles($input_name) {
		$file_bag = _elgg_services()->request->files;
		if (!$file_bag->has($input_name)) {
			return false;
		}

		$files = $file_bag->get($input_name);
		if (!$files) {
			return [];
		}
		if (!is_array($files)) {
			$files = [$files];
		}
		return $files;
	}

	/**
	 * Save uploaded files
	 *
	 * @param string $input_name Form input name
	 * @param array  $attributes File attributes
	 * @return ElggFile[]
	 */
	protected Static function saveUploadedFiles($input_name, array $attributes = []) {

		$files = [];

		$uploaded_files = self::getUploadedFiles($input_name);
		$subtype = elgg_extract('subtype', $attributes, 'file', false);
		unset($attributes['subtype']);
		$container_guid = elgg_extract('container_guid', $attributes, elgg_get_logged_in_user_guid(), false);
		unset($attributes['container_guid']);

		$class = elgg_get_entity_class('object', $subtype);
		if (!$class || !class_exists($class) || !is_subclass_of($class, ElggFile::class)) {
			$class = ElggFile::class;
		}
		
		foreach ($uploaded_files as $upload) {
			if (!$upload->isValid()) {
				$error = new \stdClass();
				$error->error = elgg_get_friendly_upload_error($upload->getError());
				$files[] = $error;
				continue;
			}

			$file = new $class();
			$file->setSubtype($subtype);
			$file->container_guid = $container_guid;
			foreach ($attributes as $key => $value) {
				$file->$key = $value;
			}

			$old_filestorename = '';
			if ($file->exists()) {
				$old_filestorename = $file->getFilenameOnFilestore();
			}

			$originalfilename = $upload->getClientOriginalName();
			$file->originalfilename = $originalfilename;
			if (empty($file->title)) {
				$file->title = htmlspecialchars($file->originalfilename, ENT_QUOTES, 'UTF-8');
			}

			$file->upload_time = time();
			$prefix = $file->filestore_prefix ? : 'file';
			$prefix = trim($prefix, '/');
			$filename = elgg_strtolower("$prefix/{$file->upload_time}{$file->originalfilename}");
			$file->setFilename($filename);
			$file->filestore_prefix = $prefix;

			$hook_params = [
				'file' => $file,
				'upload' => $upload,
			];

			$uploaded = elgg_trigger_event_results('upload', 'file', $hook_params);
			if ($uploaded !== true && $uploaded !== false) {
				$filestorename = $file->getFilenameOnFilestore();
				try {
					$uploaded = $upload->move(pathinfo($filestorename, PATHINFO_DIRNAME), pathinfo($filestorename, PATHINFO_BASENAME));
				} catch (FileException $ex) {
					elgg_log($ex->getMessage(), 'ERROR');
					$uploaded = false;
				}
			}

			if (!$uploaded) {
				$error = new \stdClass();
				$error->error = elgg_echo('dropzone:file_not_entity');
				$files[] = $error;
				continue;
			}

			if ($old_filestorename && $old_filestorename != $file->getFilenameOnFilestore()) {
				// remove old file
				unlink($old_filestorename);
			}
			_elgg_services()->events->triggerAfter('upload', 'file', $file);

			if (!$file->save() || !$file->exists()) {
				$file->delete();
				$error = new \stdClass();
				$error->error = elgg_echo('dropzone:file_not_entity');
				$files[] = $error;
				continue;
			}

			if ($file->saveIconFromElggFile($file)) {
				$file->thumbnail = $file->getIcon('small')->getFilename();
				$file->smallthumb = $file->getIcon('medium')->getFilename();
				$file->largethumb = $file->getIcon('large')->getFilename();
			}

			$success = new \stdClass();
			$success->file = $file;
			$files[] = $success;
		}

		return $files;
	}    
       
}
