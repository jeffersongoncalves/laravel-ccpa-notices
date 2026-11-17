<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use JeffersonGoncalves\CcpaNotices\Facades\CcpaNotices;
use JeffersonGoncalves\CcpaNotices\Models\ProcessingActivity;
use JeffersonGoncalves\CcpaNotices\Tests\Fixtures\Customer;

function checkout(): ProcessingActivity
{
    return ProcessingActivity::create([
        'name' => 'Checkout',
        'purpose' => 'Process orders and payments',
        'consumers' => ['customers'],
        'models' => [Customer::class],
        'sources' => ['directly from you'],
        'recipients' => [['name' => 'Stripe', 'role' => 'service_provider'], ['name' => 'AdNetwork', 'role' => 'third_party']],
        'retention' => '5 years after the last order',
    ]);
}

it('exports what the privacy policy discloses', function () {
    checkout();

    $export = CcpaNotices::export();

    expect($export['business']['name'])->toBe('Acme Inc.')
        ->and($export['categories'])->toBe(['commercial', 'identifiers'])
        ->and($export['sensitive'])->toBe(['identifiers'])
        ->and($export['sold'])->toBe(['commercial'])
        ->and($export['shared'])->toBe(['identifiers'])
        ->and($export['activities'][0])
        ->name->toBe('Checkout')
        ->categories->toBe(['commercial', 'identifiers'])
        ->and($export['activities'][0]['personal_information'])->toHaveCount(3)
        ->and(CcpaNotices::sellsOrShares())->toBeTrue()
        ->and(CcpaNotices::collectsSensitive())->toBeTrue();
});

it('prints the notices and reminds you of the links', function () {
    checkout();

    Artisan::call('ccpa:notices');
    expect(Artisan::output())->toContain('Checkout')->toContain('Stripe')->toContain('Do Not Sell or Share')->toContain('Limit the Use');

    Artisan::call('ccpa:notices', ['--json' => true]);
    expect(json_decode(Artisan::output(), true)['sold'])->toBe(['commercial']);
});

it('renders the privacy policy', function () {
    checkout();

    $html = Blade::render('<x-ccpa-notices::privacy-policy requests-url="/privacy/requests" opt-out-url="/opt-out" limit-url="/limit" />');

    expect($html)->toContain('This policy explains how Acme Inc.')
        ->toContain('Commercial information, Identifiers')
        ->toContain('Sensitive personal information: Identifiers.')
        ->toContain('Process orders and payments')
        ->toContain('Stripe (service provider)')
        ->toContain('we sold these categories: Commercial information')
        ->toContain('cross-context behavioral advertising: Identifiers')
        ->toContain('Correct inaccurate personal information')
        ->toContain('1-800-555-0100')
        ->toContain('href="/opt-out"')
        ->toContain('Do Not Sell or Share My Personal Information')
        ->toContain('Limit the Use of My Sensitive Personal Information');

    app()->setLocale('es');

    expect(Blade::render('<x-ccpa-notices::privacy-policy />'))
        ->toContain('Política de privacidad')
        ->toContain('Información comercial');
});

it('renders the notice at collection', function () {
    checkout();

    expect(Blade::render('<x-ccpa-notices::notice-at-collection policy-url="/privacy" opt-out-url="/opt-out" />'))
        ->toContain('Notice at collection')
        ->toContain('We use them for: Process orders and payments.')
        ->toContain('We sell or share some of this information.')
        ->toContain('href="/privacy"')
        ->toContain('href="/opt-out"');
});

it('hides the links you do not need', function () {
    config()->set('ccpa.models', []);

    expect(trim(Blade::render('<x-ccpa-notices::opt-out-links opt-out-url="/opt-out" limit-url="/limit" />')))->toBe('');
});
