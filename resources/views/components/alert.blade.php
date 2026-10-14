@props(['type' => 'info'])

@php
    $icon = 'ℹ️';
    if ($type === 'success') {
        $icon = '✅';
    } elseif ($type === 'danger') {
        $icon = '❌';
    } elseif ($type === 'warning') {
        $icon = '⚠️';
    }
@endphp

<div class="alert alert-{{ $type }}" style="margin-bottom: 20px;">
    <span style="font-size: 1.25rem;">{{ $icon }}</span>
    <div style="flex: 1;">
        {{ $slot }}
    </div>
</div>
