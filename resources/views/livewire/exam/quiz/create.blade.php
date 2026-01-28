<?php

use App\Models\Exam;
use App\Models\Question;
use App\Models\Standard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;

new class extends Component {
    public string $standard_id = '';
    public Collection $standards;
    public Collection $chapters;
    public array $selected_chapters = [];
    public int $total_questions = 0;

    public string $title = '';
    public string $que_qty = '';
    public string $start_date;
    public string $start_time;
    public string $duration;
    public string $end_date;
    public string $end_time;

    protected function rules(): array
    {
        return [
            'standard_id' => ['required', 'exists:standards,id'],
            'title' => ['required', 'min:3'],
            'que_qty' => ['required', 'numeric'],
            'start_date' => ['required'],
            'start_time' => ['required'],
            'duration' => ['required', 'numeric'],
            'end_date' => ['required'],
            'end_time' => ['required'],
        ];
    }


    public function mount(): void
    {
        $this->standards = Standard::all();
        $this->chapters = new Collection();
    }

    public function updatedStandardId(): void
    {
        $this->chapters = Standard::find($this->standard_id)?->chapters ?? new Collection();
        $this->selected_chapters = [];
        $this->total_questions = 0;
    }

    public function updatedSelectedChapters(): void
    {
        $this->total_questions = $this->chapters
            ->whereIn('id', $this->selected_chapters)
            ->sum(fn($chapter) => $chapter->questions->count());
    }


    public function add_exam(): void
    {
        $this->validate();
        if ($this->que_qty > $this->total_questions)
        {
            $this->addError('que_qty', 'تعداد زیاد انتخاب شده');
            return;
        }

        $questionIds = Question::whereIn('chapter_id', $this->selected_chapters)
            ->pluck('id')
            ->shuffle()
            ->take($this->que_qty)
            ->toArray();
        sort($questionIds);
        Exam::create(
            [
                'type' => 'quiz',
                'standard_id' => $this->standard_id,
                'title' => $this->title,
                'que_qty' => $this->que_qty,
                'que_ids' => json_encode($questionIds),
                'start' => Carbon::parse("{$this->start_date} {$this->start_time}"),
                'duration' => $this->duration,
                'end' => Carbon::parse("{$this->end_date} {$this->end_time}"),
                'created_by' => auth()->id(),
            ]
        );

        $this->redirectRoute('exam.index');
    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('آزمونهای کتبی')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('exam.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak class="text-blue-500">{{__('آزمونها')}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{__('کوئیز جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <flux:separator variant="subtle"/>

    <form wire:submit="add_exam" class="grid mt-5 sm:w-[400px]" autocomplete="off" autofocus>

        <!-- Standard select menu... -->
        <flux:select wire:model.live="standard_id" variant="listbox" placeholder="استانداردی انتخاب کنید ..."
                     label="استاندارد" searchable class="mb-3">
            @foreach ($standards as $standard)
                <flux:select.option value="{{$standard->id}}">{{ $standard->name_fa }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:checkbox.group wire:model.live="selected_chapters" variant="cards" class="flex-col mb-4"
                             wire:key="{{ $standard_id }}">
            <div wire:loading wire:target="standard_id" class="text-amber-500 dark:text-amber-300">
                <flux:icon.loading/>
            </div>
            @if($standard_id and $chapters->isEmpty())
                <flux:text>{{__('فصلی برای استاندارد تعریف نشده...')}}</flux:text>
            @endif
            @foreach ($chapters as $chapter)
                @php
                    $questionCount = $chapter->questions->count();
                @endphp
                @if($questionCount > 0)
                    <flux:checkbox
                        value="{{ $chapter->id }}"
                        label="{{$chapter->number.' - '.$chapter->title }}"
                        description="{{ __('تعداد سوالات: ') }}{{ $questionCount }}" class="cursor-pointer"
                    />
                @else
                    <flux:checkbox
                        label="{{ $chapter->number.' - '.$chapter->title }}"
                        description="(بدون سؤال)" disabled
                    />

                @endif
            @endforeach
        </flux:checkbox.group>

        @if($standard_id and $chapters->isNotEmpty())
            <x-my.flt_lbl name="title" label="{{__('عنوان آزمون:')}}" required/>

            <flux:badge color="orange" class="mt-4">{{'تعداد سوالات انتخاب شده: '.$total_questions}}</flux:badge>

            <div class="grid grid-cols-2 space-x-3 mt-4">
                <x-my.flt_lbl name="que_qty" label="{{__('تعداد سوالها:')}} " required maxlength="3"/>
                <x-my.flt_lbl name="duration" label="{{__('مدت آزمون (دقیقه):')}} " required/>
            </div>

            <flux:field class="mt-4">
                <flux:label>{{__('تاریخ و ساعت شروع')}}</flux:label>
                <div class="grid grid-cols-2 space-x-3">
                    <flux:date-picker locale="fa-IR" wire:model="start_date" with-today selectable-header/>
                    <flux:time-picker wire:model="start_time" type="input" dir="ltr" align="center"/>
                </div>
            </flux:field>

            <flux:field class="mt-4 mb-4">
                <flux:label>{{__('تاریخ و ساعت پایان')}}</flux:label>
                <div class="grid grid-cols-2 space-x-3">
                    <flux:date-picker locale="fa-IR" wire:model="end_date" with-today selectable-header/>
                    <flux:time-picker wire:model="end_time" type="input" dir="ltr" align="center"/>
                </div>
            </flux:field>




            <div class="flex justify-between flex-row-reverse">
                <flux:button type="submit" variant="primary" color="sky" size="sm"
                             class="cursor-pointer">{{__('ذخیره')}}</flux:button>
                <flux:button href="{{route('exam.index')}}" variant="primary" color="zinc" wire:navigate
                             size="sm">{{__('انصراف')}}</flux:button>
            </div>
        @endif
    </form>
</div>
