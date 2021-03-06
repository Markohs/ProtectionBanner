<?php

return [
    /*
     * Array of environments were this middleware is *ENABLED*
     * Default: ['stage', 'prod']
     */

    'enabled_environments' => ['stage', 'prod', 'production'],

    /*
     * Set up a array of simple text patterns wich will be EXCLUDED from URL redirection, matched in  * regexp fashion
     * example: ['api/example','cookie-notice']
     * Default: none
     */

    'whitelist' => [],

    /*
     * Autoregister in these middleware groups
     * Default: ['web']
     */

    'autoregister' => ['web'],

    /*
     * Channel to log accept info, if necessary
     * Default: null
     * example: "accepts"
     */
    'logchannel' => null,

    /*
     * Name of the cookie and get parameter where we store acceptance of conditions
     * Default: 'accepted_terms'
     */
    'ses_name' => 'accepted_terms',

];
