<?php

use App\Models\Exam;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public ?int $highlightedExamId = null;

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    #[Computed]
    public function exams()
    {
        return Exam::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('exam-created')]
    public function afterCreated($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);

        $exam = Exam::find($id);
        if (!$exam) {
            return;
        }
        $beforeCount = Exam::where('id', '>', $exam->id)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);

        $this->highlightedExamId = $exam->id;
    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('آزمونهای کتبی')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('لیست آزمونها')}}</flux:text>
        <livewire:exam.create_type_select/>
    </div>
    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->exams">
        <flux:table.columns>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'type'" :direction="$sortDirection"
                               wire:click="sort('type')">
                {{__('نوع')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('استاندارد')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('عنوان')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{__('تعداد سوالات')}}
            </flux:table.column>
            <flux:table.column align="center">
                {{__('مدت آزمون')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'start_date_time'" :direction="$sortDirection"
                               wire:click="sort('start_date_time')">
                {{__('زمان آزمون')}}
            </flux:table.column>

            <flux:table.column>{{'وضعیت'}}</flux:table.column>

            <flux:table.column align="center">
                {{__('تاخیر شروع')}}
            </flux:table.column>



            <flux:table.column align="center">
                {{__('توسط')}}
            </flux:table.column>


            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>

            <flux:table.column>{{ __('عملیات') }}</flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @if($highlightedExamId)
                <div x-data x-init="setTimeout(() => $wire.set('highlightedExamId', null), 2000)"></div>
            @endif
            @foreach ($this->exams as $exam)
                @php($class='')

                @if($highlightedExamId === $exam->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif

                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$exam->id">
                    <flux:table.cell align="center">{{ $exam->id }}</flux:table.cell>
                    <flux:table.cell>{{ $exam->type }}</flux:table.cell>
                    <flux:table.cell class="grid gap-y-1 text-wrap" align="center">
                        <div>{{ $exam->standard->name_fa }}</div>
                        <div class="tracking-widest">{{ $exam->standard->code }}</div>

                    </flux:table.cell>
                    <flux:table.cell align="center">{{ $exam->title }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $exam->que_qty }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $exam->duration }}{{__(' دقیقه')}}</flux:table.cell>

                    <flux:table.cell align="center">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $exam->jalali_start_date_time)[0] }}</div>
                            <div class="text-xs">
                                {{ substr($exam->jalali_start_date_time, 11, 5) }}
                                {{ substr($exam->jalali_start_date_time, 17) }}
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{__('وضعیت')}}
                    </flux:table.cell>

                    <flux:table.cell>{{ $exam->delay }}{{__(' دقیقه')}}</flux:table.cell>
                    <flux:table.cell align="center">
                        <div>{{ $exam->maker->profile->f_name_fa }}</div>
                        <div>{{ $exam->maker->profile->l_name_fa }}</div>

                    </flux:table.cell>

                    <flux:table.cell align="center">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $exam->jalali_created_at)[0] }}</div>
                            <div class="text-xs">
                                {{ substr($exam->jalali_created_at, 11, 5) }}
                                <span class="text-stone-500">{{ substr($exam->jalali_created_at, 17) }}</span>
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell align="center">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $exam->jalali_updated_at)[0] }}</div>
                            <div class="text-xs">
                                {{ substr($exam->jalali_updated_at, 11, 5) }}
                                <span class="text-stone-500">{{ substr($exam->jalali_updated_at, 17) }}</span>
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:link href="{{ route('participant.index', $exam) }}" variant="subtle" wire:navigate x-data="{ loading: false }" @click="loading = true">
                                <span x-show="!loading" class="text-blue-500">{{ __('آزمون دهنده ها') }}</span>
                                <flux:icon.loading x-show="loading" class="size-5 text-blue-500 mr-3"/>
                            </flux:link>
                        </div>
                    </flux:table.cell>

                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
