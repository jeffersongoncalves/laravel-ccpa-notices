<?php

namespace JeffersonGoncalves\CcpaNotices\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Ccpa\Attributes\PersonalData;
use JeffersonGoncalves\Ccpa\Concerns\InteractsWithPersonalData;
use JeffersonGoncalves\Ccpa\Contracts\HasPersonalData;
use JeffersonGoncalves\Ccpa\Enums\DataCategory;
use JeffersonGoncalves\Ccpa\Enums\PiCategory;

#[PersonalData('email', piCategory: PiCategory::Identifiers, retention: '5 years', shared: true)]
#[PersonalData('purchases', piCategory: PiCategory::Commercial, sold: true)]
#[PersonalData('ssn', category: DataCategory::Sensitive, piCategory: PiCategory::Identifiers)]
class Customer extends Model implements HasPersonalData
{
    use InteractsWithPersonalData;

    protected $table = 'customers';
}
