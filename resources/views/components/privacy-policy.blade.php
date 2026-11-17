@props(['requestsUrl' => null, 'optOutUrl' => null, 'limitUrl' => null])
@php
    $notices = app(\JeffersonGoncalves\CcpaNotices\NoticeManager::class);
    $data = $notices->export();
    $updatedAt = \JeffersonGoncalves\CcpaNotices\Models\ProcessingActivity::query()->max('updated_at');
    $label = fn (string $group, ?string $key): ?string => $key === null ? null
        : (\Illuminate\Support\Facades\Lang::has("ccpa-notices::policy.{$group}.{$key}") ? __("ccpa-notices::policy.{$group}.{$key}") : $key);
    $list = fn (array $keys, string $group): string => collect($keys)->map(fn (string $key): string => $label($group, $key))->implode(', ');
@endphp
<article {{ $attributes->merge(['class' => 'ccpa-privacy-policy']) }}>
    <h2>{{ __('ccpa-notices::policy.title') }}</h2>
    @if ($updatedAt)
        <p>{{ __('ccpa-notices::policy.updated_at', ['date' => \Illuminate\Support\Carbon::parse($updatedAt)->translatedFormat('m/d/Y')]) }}</p>
    @endif
    @if ($data['business']['name'] ?? null)
        <p>{{ __('ccpa-notices::policy.intro', ['name' => $data['business']['name']]) }}</p>
    @endif

    <section>
        <h3>{{ __('ccpa-notices::policy.collected') }}</h3>
        <p>{{ $data['categories'] === [] ? __('ccpa-notices::policy.none') : $list($data['categories'], 'categories') }}</p>
        @if ($data['sensitive'] !== [])
            <p>{{ __('ccpa-notices::policy.sensitive', ['categories' => $list($data['sensitive'], 'categories')]) }}</p>
        @endif
    </section>

    <section>
        <h3>{{ __('ccpa-notices::policy.processing') }}</h3>
        @foreach ($data['activities'] as $activity)
            <section>
                <h4>{{ $activity['name'] }}</h4>
                <dl>
                    <dt>{{ __('ccpa-notices::policy.purpose') }}</dt>
                    <dd>{{ $activity['purpose'] }}</dd>
                    @if ($activity['categories'] !== [] || $activity['data_categories'] !== [])
                        <dt>{{ __('ccpa-notices::policy.categories_label') }}</dt>
                        <dd>{{ collect($activity['categories'])->map(fn ($c) => $label('categories', $c))->merge($activity['data_categories'])->implode(', ') }}</dd>
                    @endif
                    @if ($activity['sources'] !== [])
                        <dt>{{ __('ccpa-notices::policy.sources') }}</dt>
                        <dd>{{ implode(', ', $activity['sources']) }}</dd>
                    @endif
                    @if ($activity['recipients'] !== [])
                        <dt>{{ __('ccpa-notices::policy.recipients') }}</dt>
                        <dd>{{ collect($activity['recipients'])->map(fn (array $r): string => $r['name'].(isset($r['role']) ? ' ('.$label('roles', $r['role']).')' : ''))->implode(', ') }}</dd>
                    @endif
                    @if ($activity['retention'])
                        <dt>{{ __('ccpa-notices::policy.retention') }}</dt>
                        <dd>{{ $activity['retention'] }}</dd>
                    @endif
                </dl>
            </section>
        @endforeach
    </section>

    <section>
        <h3>{{ __('ccpa-notices::policy.sale_sharing') }}</h3>
        @if ($data['sold'] === [] && $data['shared'] === [])
            <p>{{ __('ccpa-notices::policy.no_sale') }}</p>
        @else
            @if ($data['sold'] !== [])<p>{{ __('ccpa-notices::policy.sold', ['categories' => $list($data['sold'], 'categories')]) }}</p>@endif
            @if ($data['shared'] !== [])<p>{{ __('ccpa-notices::policy.shared', ['categories' => $list($data['shared'], 'categories')]) }}</p>@endif
            <p>{{ __('ccpa-notices::policy.minors') }}</p>
        @endif
    </section>

    <section>
        <h3>{{ __('ccpa-notices::policy.rights') }}</h3>
        <ul>
            @foreach ((array) __('ccpa-notices::policy.rights_list') as $right)
                <li>{{ $right }}</li>
            @endforeach
        </ul>
        <p>{{ __('ccpa-notices::policy.no_discrimination') }}</p>
        <p>{{ __('ccpa-notices::policy.agent') }}</p>
        <p>
            {{ __('ccpa-notices::policy.how') }}
            @if ($requestsUrl)<a href="{{ $requestsUrl }}">{{ __('ccpa-notices::policy.requests_link') }}</a>@endif
            @if ($data['business']['toll_free_number'] ?? null) &middot; {{ $data['business']['toll_free_number'] }}@endif
            @if ($data['business']['contact'] ?? null) &middot; {{ $data['business']['contact'] }}@endif
        </p>
        <x-ccpa-notices::opt-out-links :opt-out-url="$optOutUrl" :limit-url="$limitUrl" />
    </section>
</article>
