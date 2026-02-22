<?php

namespace App\Filament\Resources\DailyMalaCountResource\Widgets;

use App\Models\DailyMalaCount;
use App\Models\MalaJapaLog;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MalaCalendarWidget extends FullCalendarWidget implements HasSchemas
{
    use InteractsWithSchemas;

    public Model|string|null $model = DailyMalaCount::class;

    protected string $view = 'filament.widgets.mala-calendar-widget';

    public ?int $month = null;
    public ?int $year = null;
    public ?string $monthName = null;

    protected int|string|array $columnSpan = 'full';

    public function fetchEvents(array $fetchInfo): array
    {
        $start = Carbon::parse($fetchInfo['start']);
        $end = Carbon::parse($fetchInfo['end']);

        // Use the middle of the range to identify which month we are looking at
        $midpoint = $start->copy()->addDays($start->diffInDays($end) / 2);

        $this->month = $midpoint->month;
        $this->year = $midpoint->year;
        $this->monthName = $midpoint->format('F');

        return DailyMalaCount::query()
            ->where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->select(['id', 'name', 'mala_count', 'start', 'end', 'allDay'])
            ->get()
            ->map(
                fn(DailyMalaCount $event) => [
                    'id' => $event->id,
                    'title' => (string) $event->mala_count,
                    'start' => $event->start,
                    'end' => $event->end,
                    'allDay' => $event->allDay,
                    'backgroundColor' => '#10b981', // Emerald green
                    'borderColor' => '#059669',
                ]
            )
            ->all();
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'stats' => $this->getStats(),
        ]);
    }

    protected function getStats(): array
    {
        $lastJapa = MalaJapaLog::latest('japa_date')->first();
        $lastJapaDateObj = $lastJapa ? Carbon::parse($lastJapa->japa_date)->startOfDay() : null;
        $lastJapaDate = $lastJapaDateObj ? $lastJapaDateObj->format('d-m-Y') : 'N/A';

        $pendingDays = 0;
        if ($lastJapaDateObj) {
            $today = now()->startOfDay();
            if ($lastJapaDateObj->lessThan($today)) {
                $pendingDays = $lastJapaDateObj->diffInDays($today);
            }
        }

        $lastJapaDisplay = $lastJapaDate . ($pendingDays > 0 ? " ({$pendingDays} days)" : "");

        $reportDate = ($this->month && $this->year)
            ? Carbon::createFromDate($this->year, $this->month, 1)
            : now();

        $startOfMonth = $reportDate->copy()->startOfMonth();
        $endOfMonth = $reportDate->copy()->endOfMonth();
        $daysInMonth = $reportDate->daysInMonth;
        $displayMonthName = $this->monthName ?? $reportDate->format('F');

        $totalMonthlyCount = DailyMalaCount::whereBetween('start', [$startOfMonth->toDateTimeString(), $endOfMonth->toDateTimeString()])
            ->sum('mala_count');

        $targetCount = $daysInMonth * 7;
        $percentage = $targetCount > 0 ? round(($totalMonthlyCount / $targetCount) * 100, 1) : 0;

        return [
            Stat::make('Last Japa Date', $lastJapaDisplay)
                ->description('From Mala Japa Logs')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($pendingDays > 0 ? 'danger' : 'success'),

            Stat::make($displayMonthName . ' Progress', "{$totalMonthlyCount} / {$targetCount} ({$percentage}%)")
                ->description("Total vs target (7/day)")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($totalMonthlyCount >= $targetCount ? 'success' : 'warning'),
        ];
    }

    public function getStatsSchema(): Schema
    {
        return Schema::make($this)
            ->components([
                $this->getSectionContentComponent(),
            ]);
    }

    public function getSectionContentComponent(): Section
    {
        return Section::make()
            ->schema($this->getStats())
            ->columns(count($this->getStats()))
            ->contained(false)
            ->gridContainer();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make('create')
                ->extraAttributes(['style' => 'display: none'])
                ->modalWidth('sm')
                ->modalHeading(fn(array $arguments) => new HtmlString("Create Daily Mala Count <br> <span class='text-sm font-normal text-gray-500'>" . (isset($arguments['start']) ? Carbon::parse($arguments['start'])->format('d-m-Y') : now()->format('d-m-Y')) . "</span>"))
                ->createAnother(false)
                ->mountUsing(
                    function (Action $action, array $arguments) {
                        $start = data_get($arguments, 'start');

                        $action->fillForm([
                            'start' => $start ? Carbon::parse($start)->toDateTimeString() : now()->toDateTimeString(),
                            'end' => data_get($arguments, 'end') ? Carbon::parse(data_get($arguments, 'end'))->toDateTimeString() : now()->toDateTimeString(),
                            'allDay' => data_get($arguments, 'allDay') ?? true,
                            'mala_count' => 7,
                        ]);
                    }
                )
                ->mutateFormDataUsing(function (array $data, CreateAction $action): array {
                    $arguments = $action->getArguments();

                    $data['name'] = (string) ($data['mala_count'] ?? 0);
                    $data['start'] = data_get($arguments, 'start') ?? now();
                    $data['end'] = data_get($arguments, 'end') ?? now();
                    $data['allDay'] = data_get($arguments, 'allDay') ?? true;

                    return $data;
                })
                ->after(function (DailyMalaCount $record, $livewire) {
                    $count = (int) ($record->mala_count ?? 0);
                    if ($count <= 0)
                        return;

                    $lastLog = MalaJapaLog::latest('japa_date')->first();
                    $startDate = $lastLog ? Carbon::parse($lastLog->japa_date) : now()->subDay();

                    $logs = [];
                    $now = now();
                    for ($i = 0; $i < $count; $i++) {
                        $startDate->addDay();
                        $logs[] = [
                            'japa_date' => $startDate->toDateString(),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    MalaJapaLog::insert($logs);

                    // Force a refresh of the calendar logic using the correct package event
                    $livewire->dispatch('filament-fullcalendar--refresh');
                }),
        ];
    }

    protected function modalActions(): array
    {
        return [
            // Edit and Delete disabled as per user request
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(array $event): bool
    {
        return false;
    }

    public function config(): array
    {
        return [
            'selectable' => true,
            'lazyFetching' => true,
            'dayMaxEvents' => 6,
            'fixedWeekCount' => false,
            'showNonCurrentDates' => true,
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
            ],
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Hidden::make('start'),
            TextInput::make('mala_count')
                ->label('Mala Count')
                ->numeric()
                ->required()
                ->default(7)
                ->autofocus(),
        ];
    }

    public function eventClassNames(): string
    {
        return <<<JS
            function({ event }) {
                return ['mala-count-badge'];
            }
        JS;
    }

    public function eventContent(): string
    {
        return <<<JS
            function(arg) {
                return { 
                    html: '<div class="mala-count-wrapper"><span>' + arg.event.title + '</span></div>' 
                }
            }
        JS;
    }
}
