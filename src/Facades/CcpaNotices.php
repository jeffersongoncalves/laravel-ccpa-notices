<?php

namespace JeffersonGoncalves\CcpaNotices\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\CcpaNotices\NoticeManager;

/**
 * @method static array{business: array<string, mixed>, categories: list<string>, sensitive: list<string>, sold: list<string>, shared: list<string>, activities: list<array<string, mixed>>} export()
 * @method static bool collectsSensitive()
 * @method static bool sellsOrShares()
 *
 * @see NoticeManager
 */
class CcpaNotices extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NoticeManager::class;
    }
}
