<?php

namespace JeffersonGoncalves\CcpaNotices;

use JeffersonGoncalves\Ccpa\Ccpa;
use JeffersonGoncalves\CcpaNotices\Models\ProcessingActivity;

class NoticeManager
{
    public function __construct(protected Ccpa $ccpa) {}

    /**
     * Everything the privacy policy has to disclose (Cal. Civ. Code §1798.130(a)(5); CCPA Regulations §7011):
     * the business, each activity with its declared personal information, and the categories collected,
     * sensitive, sold and shared across all activities.
     *
     * @return array{business: array<string, mixed>, categories: list<string>, sensitive: list<string>, sold: list<string>, shared: list<string>, activities: list<array<string, mixed>>}
     */
    public function export(): array
    {
        $inventory = collect($this->ccpa->inventory());
        $byModel = $inventory->groupBy('model');
        $categories = fn ($rows): array => $rows->pluck('pi_category')->filter()->unique()->sort()->values()->all();

        return [
            'business' => (array) config('ccpa-notices.business', []),
            'categories' => $categories($inventory),
            'sensitive' => $categories($inventory->where('category', 'sensitive')),
            'sold' => $categories($inventory->where('sold', true)),
            'shared' => $categories($inventory->where('shared', true)),
            'activities' => ProcessingActivity::query()->orderBy('name')->get()
                ->map(function (ProcessingActivity $activity) use ($byModel, $categories): array {
                    $rows = collect($activity->models ?? [])->flatMap(fn (string $model) => $byModel->get($model, collect()));

                    return [
                        'name' => $activity->name,
                        'purpose' => $activity->purpose,
                        'consumers' => $activity->consumers ?? [],
                        'categories' => $categories($rows),
                        'data_categories' => $activity->data_categories ?? [],
                        'personal_information' => $rows->map(fn (array $row): array => [
                            'table' => $row['table'],
                            'column' => $row['column'],
                            'pi_category' => $row['pi_category'],
                            'sensitive' => $row['category'] === 'sensitive',
                            'sold' => $row['sold'],
                            'shared' => $row['shared'],
                            'retention' => $row['retention'],
                        ])->values()->all(),
                        'sources' => $activity->sources ?? [],
                        'recipients' => $activity->recipients ?? [],
                        'retention' => $activity->retention,
                    ];
                })
                ->values()
                ->all(),
        ];
    }

    /** You must post a "Limit the Use of My Sensitive Personal Information" link when you collect any (§1798.135(a)(2)). */
    public function collectsSensitive(): bool
    {
        return collect($this->ccpa->inventory())->contains('category', 'sensitive');
    }

    /** You must post a "Do Not Sell or Share My Personal Information" link when you sell or share any (§1798.135(a)(1)). */
    public function sellsOrShares(): bool
    {
        return collect($this->ccpa->inventory())->contains(fn (array $row): bool => $row['sold'] || $row['shared']);
    }
}
