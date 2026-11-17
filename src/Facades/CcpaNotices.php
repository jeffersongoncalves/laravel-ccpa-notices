<?php

namespace JeffersonGoncalves\CcpaNotices\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\CcpaNotices\CcpaNotices
 */
class CcpaNotices extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-ccpa-notices';
    }
}
