<?php

use App\Models\WrittenQuestion;
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

    public ?int $highlightedId = null;

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
    public function written_questions()
    {
        return WrittenQuestion::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('wq-created')]
    public function afterCreated($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);

        $wq = WrittenQuestion::find($id);
        if (!$wq) {
            return;
        }
        $beforeCount = WrittenQuestion::where('id', '>', $wq->id)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);
        $this->highlightedId = $wq->id;
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات محصولات')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('نمونه سوالات پرتکرار کتبی')}}</flux:text>

        {{-- Create Component --}}
        <livewire:product.written-question.create/>
    </div>
    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->written_questions" class="inline">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('شناسه')}}
            </flux:table.column>
            <flux:table.column align="center">{{__('شناسه مدل')}}</flux:table.column>
            <flux:table.column align="center">{{__('کد استاندارد')}}</flux:table.column>
            <flux:table.column align="center">{{__('نام استاندارد')}}</flux:table.column>
            <flux:table.column align="center">{{__('تعداد قیمت گذاری شده')}}</flux:table.column>
            <flux:table.column align="center">{{__('عملیات')}}</flux:table.column>
        </flux:table.columns>

        @if($highlightedId)
            <div x-data x-init="setTimeout(() => $wire.set('highlightedId', null), 1000)"></div>
        @endif
        <flux:table.rows>
            @foreach ($this->written_questions as $written_question)
                @php($class='')
                @if($highlightedId === $written_question->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif

                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$written_question->id">
                    <flux:table.cell align="center">{{ $written_question->id }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $written_question->model_id }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $written_question->standard->code }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $written_question->standard->name_fa }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $written_question->products->count() }}</flux:table.cell>
                    <flux:table.cell align="center">
                        <div class="inline-flex items-center gap-2">
                            <flux:link href="{{ route('written_questions.price_list', $written_question) }}" variant="subtle" wire:navigate x-data="{ loading: false }" @click="loading = true">
                                <span x-show="!loading" class="text-blue-500">{{ __('قیمت گذاری') }}</span>
                                <flux:icon.loading x-show="loading" class="size-3.5 text-blue-500 mr-3"/>
                            </flux:link>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
