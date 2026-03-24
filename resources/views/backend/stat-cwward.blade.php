@props([
    'label',
    'value',
    'icon',
    'change' => null,
    'changeType' => 'success', // success | danger | warning | info
    'prefix' => null,          // Optional (currency / symbol)
])

<div class="stat-card">
    <div class="stat-header">

        <div>
            <div class="stat-label">
                {{ $label }}
            </div>

            <div class="stat-value">
                @if($prefix)
                    <span class="stat-prefix">{{ $prefix }}</span>
                @endif

                {{ $value }}
            </div>
        </div>

        <div class="stat-icon bg-{{ $changeType }}">
            <span class="material-icons-round">
                {{ $icon }}
            </span>
        </div>

    </div>

    @if($change)
        <div class="stat-change text-{{ $changeType }}">
            <span class="material-icons-round" style="font-size: 16px;">
                {{ $changeType === 'danger' ? 'trending_down' : 'trending_up' }}
            </span>

            {{ $change }}
        </div>
    @endif
</div>
