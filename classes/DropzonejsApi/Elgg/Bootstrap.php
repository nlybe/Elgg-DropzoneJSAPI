<?php
/**
 * Elgg DropzoneJS API plugin
 * @package dropzonejs_api
 */

namespace DropzonejsApi\Elgg;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	const HANDLERS = [];
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->initViews();
	}

	/**
	 * Init views
	 *
	 * @return void
	 */
	protected function initViews() {

		// register extra css
		elgg_extend_view('elgg.css', 'dropzonejs_api/css.css');

		// dropzone.js library, use with "require('dropzonejs_api');"
		elgg_define_js('dropzonejs_api', [
			'deps' => [],
			'exports' => 'dropzonejs_api',
		]);
		
	}
}
