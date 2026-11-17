<?php

namespace JeffersonGoncalves\CcpaNotices\Commands;

use Illuminate\Console\Command;
use JeffersonGoncalves\CcpaNotices\NoticeManager;

class NoticesCommand extends Command
{
    protected $signature = 'ccpa:notices {--json : Print everything the privacy policy discloses, as JSON}';

    protected $description = 'Show what your CCPA privacy policy and notice at collection disclose';

    public function handle(NoticeManager $notices): int
    {
        $export = $notices->export();

        if ($this->option('json')) {
            $this->line((string) json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        $this->table(
            ['Activity', 'Purpose', 'Categories', 'Recipients', 'Retention'],
            array_map(fn (array $activity): array => [
                $activity['name'],
                $activity['purpose'],
                implode(', ', [...$activity['categories'], ...$activity['data_categories']]) ?: '-',
                implode(', ', array_column($activity['recipients'], 'name')) ?: '-',
                $activity['retention'] ?? '-',
            ], $export['activities']),
        );

        $this->line('Sold: '.(implode(', ', $export['sold']) ?: 'none').'. Shared: '.(implode(', ', $export['shared']) ?: 'none').'. Sensitive: '.(implode(', ', $export['sensitive']) ?: 'none').'.');

        if ($notices->sellsOrShares()) {
            $this->warn('You sell or share personal information: post a "Do Not Sell or Share My Personal Information" link.');
        }

        if ($notices->collectsSensitive()) {
            $this->warn('You collect sensitive personal information: post a "Limit the Use of My Sensitive Personal Information" link, unless you only use it for the purposes of Regulations §7027(m).');
        }

        return self::SUCCESS;
    }
}
