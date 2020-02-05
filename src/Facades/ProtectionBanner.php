<?php

namespace Markohs\ProtectionBanner\Facades;

use Illuminate\Support\Facades\Facade;

class ProtectionBanner extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'protectionbanner';
    }
}
