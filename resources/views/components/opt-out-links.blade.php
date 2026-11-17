@props(['optOutUrl' => null, 'limitUrl' => null])
@php($notices = app(\JeffersonGoncalves\CcpaNotices\NoticeManager::class))
@if (($optOutUrl && $notices->sellsOrShares()) || ($limitUrl && $notices->collectsSensitive()))
    <nav {{ $attributes->merge(['class' => 'ccpa-opt-out-links']) }}>
        @if ($optOutUrl && $notices->sellsOrShares())
            <a href="{{ $optOutUrl }}">{{ __('ccpa-notices::policy.do_not_sell') }}</a>
        @endif
        @if ($limitUrl && $notices->collectsSensitive())
            <a href="{{ $limitUrl }}">{{ __('ccpa-notices::policy.limit') }}</a>
        @endif
    </nav>
@endif
