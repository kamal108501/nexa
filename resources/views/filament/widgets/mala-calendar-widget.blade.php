@php
    $plugin = \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    {{-- Stats Section --}}
    @if ($this->getStats())
        <div class="mb-6">
            {{ $this->getStatsSchema() }}
        </div>
    @endif

    <div style="height: 24px;"></div>

    <x-filament::section>
        <div class="flex justify-end flex-1 mb-4">
            <x-filament::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />
        </div>

        <div wire:ignore x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"
            x-ignore x-data="fullcalendar({
                locale: @js($plugin->getLocale()),
                plugins: @js($plugin->getPlugins()),
                schedulerLicenseKey: @js($plugin->getSchedulerLicenseKey()),
                timeZone: @js($plugin->getTimezone()),
                config: @js($this->getConfig()),
                editable: @json($plugin->isEditable()),
                selectable: @json($plugin->isSelectable()),
                eventClassNames: {!! htmlspecialchars($this->eventClassNames(), ENT_COMPAT) !!},
                eventContent: {!! htmlspecialchars($this->eventContent(), ENT_COMPAT) !!},
                eventDidMount: {!! htmlspecialchars($this->eventDidMount(), ENT_COMPAT) !!},
                eventWillUnmount: {!! htmlspecialchars($this->eventWillUnmount(), ENT_COMPAT) !!},
            })" class="filament-fullcalendar"></div>
    </x-filament::section>

    <x-filament-actions::modals />

    <style>
        .fc-daygrid-day-events {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 2px !important;
            justify-content: center !important;
            padding-bottom: 4px !important;
        }

        .fc-daygrid-event-harness {
            margin: 0 !important;
            flex: 0 0 calc(33.333% - 2px) !important;
            display: flex !important;
            justify-content: center !important;
        }

        .mala-count-badge {
            background-color: transparent !important;
            border: none !important;
            display: block !important;
            width: 100% !important;
        }

        .mala-count-wrapper {
            background-color: #10b981 !important;
            /* Emerald-500 */
            color: white !important;
            font-weight: bold !important;
            font-size: 0.875rem !important;
            padding: 2px 8px !important;
            border-radius: 9999px !important;
            /* Rounded-full */
            display: inline-flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-width: 24px !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            margin: 2px auto !important;
        }

        .fc-event-main {
            display: flex !important;
            justify-content: center !important;
        }

        .fc-daygrid-event {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .fc-daygrid-day-frame {
            height: 100px !important;
            overflow: hidden !important;
        }

        .fc-scroller {
            height: auto !important;
        }

        .fc-daygrid-day {
            cursor: pointer !important;
        }

        .fc-day-other {
            opacity: 0.6 !important;
        }
    </style>
</x-filament-widgets::widget>