<x-filament::card class="p-6">
    @php
    $isBlocked = $stats?->trading_blocked ?? false;
    $isWarning = $stats && $stats->remaining_loss_balance <= ($stats->current_allowed_loss * 0.2);

        $statusClass = $isBlocked
        ? 'text-red-500'
        : ($isWarning ? 'text-yellow-400' : 'text-green-500');
        @endphp

        {{-- Title (same as Net P&L) --}}
        <p class="text-sm text-slate-400 mb-2 --gray-400">
            Monthly Risk Usage
        </p>

        {{-- MAIN VALUE (same size as 1,500.00) --}}
        <div class="text-4xl font-semibold text-white leading-tight mb-1">
            {{ number_format($remaining, 2) }}
        </div>

        {{-- Secondary info (same role as "Net profit") --}}
        <p class="text-sm text-slate-400 mb-1">
            {{ $usedPercent }}% used
        </p>

        {{-- Status (green / yellow / red like Net P&L) --}}
        <p class="text-sm {{ $statusClass }}">
            @if ($isBlocked)
            Trading blocked
            @elseif ($isWarning)
            Risk near limit
            @else
            Trading allowed. Stay disciplined.
            @endif
        </p>
</x-filament::card>