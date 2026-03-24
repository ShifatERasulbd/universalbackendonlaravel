@props([
    'label' => 'Label',
    'value' => '0',
    'icon' => 'info',
    'change' => '',
    'changeType' => 'default'
])

<div class="stat-card">
    <div class="stat-header">
        <div>
            <div class="stat-label">{{ $label }}</div>
            <div class="stat-value">{{ $value }}</div>
        </div>
        <div class="stat-icon">
            <span class="material-icons-round">{{ $icon }}</span>
        </div>
    </div>
    @if($change)
        <div class="stat-change text-{{ $changeType }}">
            @if($changeType === 'success')
                <span class="material-icons-round" style="font-size: 16px;">trending_up</span>
            @elseif($changeType === 'danger')
                <span class="material-icons-round" style="font-size: 16px;">trending_down</span>
            @elseif($changeType === 'warning')
                <span class="material-icons-round" style="font-size: 16px;">warning</span>
            @else
                <span class="material-icons-round" style="font-size: 16px;">info</span>
            @endif
            {{ $change }}
        </div>
    @endif
</div>

