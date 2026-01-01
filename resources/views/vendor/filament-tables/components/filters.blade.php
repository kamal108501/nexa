@php
use Filament\Tables\Enums\FiltersResetActionPosition;
@endphp

@props([
'applyAction',
'form',
'headingTag' => 'h3',
'resetActionPosition' => FiltersResetActionPosition::Header,
])

<div {{ $attributes->class(['fi-ta-filters']) }}>
    <div class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[180px]">
            {{ $form }}
        </div>
        @if ($applyAction->isVisible())
        <div>
            {{ $applyAction }}
        </div>
        @endif
        @if ($resetActionPosition === FiltersResetActionPosition::Header)
        <div>
            <x-filament::link
                :attributes="
                        \Filament\Support\prepare_inherited_attributes(
                            new \Illuminate\View\ComponentAttributeBag([
                                'color' => 'danger',
                                'tag' => 'button',
                                'wire:click' => 'resetTableFiltersForm',
                                'wire:loading.remove.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                                'wire:target' => 'resetTableFiltersForm',
                            ])
                        )
                    ">
                {{ __('filament-tables::table.filters.actions.reset.label') }}
            </x-filament::link>
        </div>
        @endif
    </div>
</div>