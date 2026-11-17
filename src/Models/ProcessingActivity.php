<?php

namespace JeffersonGoncalves\CcpaNotices\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One business or commercial purpose for which you collect personal information.
 *
 * @property string $name
 * @property string $purpose business or commercial purpose (Cal. Civ. Code §1798.140(e))
 * @property list<string>|null $consumers e.g. customers, job applicants, website visitors
 * @property list<string>|null $data_categories free text, beside the declared columns of $models
 * @property list<class-string>|null $models
 * @property list<string>|null $sources categories of sources: directly from you, your devices, service providers...
 * @property list<array{name: string, role?: string}>|null $recipients role: service_provider, contractor, third_party
 * @property string|null $retention how long, or the criteria used to decide (§1798.100(a)(3))
 */
class ProcessingActivity extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return config('ccpa-notices.table', 'ccpa_processing_activities');
    }

    protected function casts(): array
    {
        return [
            'consumers' => 'array',
            'data_categories' => 'array',
            'models' => 'array',
            'sources' => 'array',
            'recipients' => 'array',
        ];
    }
}
