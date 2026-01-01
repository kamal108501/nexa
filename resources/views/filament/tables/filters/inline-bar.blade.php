@php
// $filters: array of filter form components
// $applyFiltersAction: Filament\Actions\Action instance for the submit button
@endphp
<div class="flex flex-wrap items-end gap-4">
    @foreach ($filters as $filter)
    <div class="flex-1 min-w-[180px]">
        {!! $filter->render() !!}
    </div>
    @endforeach
    <div>
        {!! $applyFiltersAction->button() !!}
    </div>
</div>