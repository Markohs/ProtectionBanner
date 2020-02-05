<?php

return [
	/*
	 * Array of environments were this middleware is *ENABLED*
	 * Default: ['stage', 'prod']
	 */

	'envs_enabled' => ['stage', 'prod'],

	/*
	 * Set up a array of simple text patterns wich will be EXCLUDED from URL redirection, matched in  * regexp fashion
	 * example: ['api/example','cookie-notice']
	 * Default: none
	 */

	'whitelist_url' => [],

	/*
	 * Autoregister in these middleware groups
	 * Default: ['web']
	 */

	'autoregister' => ['web']

];