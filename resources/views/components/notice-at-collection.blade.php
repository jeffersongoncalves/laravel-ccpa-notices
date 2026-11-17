@props(['policyUrl' => null, 'optOutUrl' => null, 'limitUrl' => null])
@php
    $data = app(\JeffersonGoncalves\CcpaNotices\NoticeManager::class)->export();
    $label = fn (string $key): string => \Illuminate\Support\Facades\Lang::has("ccpa-notices::policy.categories.{$key}") ? __("ccpa-notices::policy.categories.{$key}") : $key;
@endphp
<aside {{ $attributes->merge(['class' => 'ccpa-notice-at-collection']) }}>
    <strong>{{ __('ccpa-notices::policy.notice_title') }}</strong>
    <p>{{ __('ccpa-notices::policy.notice_collect', ['categories' => collect($data['categories'])->map($label)->implode(', ') ?: '-']) }}</p>
    <p>{{ __('ccpa-notices::policy.notice_purposes', ['purposes' => collect($data['activities'])->pluck('purpose')->unique()->implode('; ') ?: '-']) }}</p>
    <p>{{ $data['sold'] === [] && $data['shared'] === [] ? __('ccpa-notices::policy.no_sale') : __('ccpa-notices::policy.notice_sold') }}</p>
    @if ($policyUrl)
        <p><a href="{{ $policyUrl }}">{{ __('ccpa-notices::policy.notice_policy') }}</a></p>
    @endif
    <x-ccpa-notices::opt-out-links :opt-out-url="$optOutUrl" :limit-url="$limitUrl" />
</aside>
