<?php

use App\Models\Field;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public string $sortBy = 'title';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    public ?int $highlightedFieldId = null;

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
    public function fields()
    {
        return Field::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->withCount('standards')
            ->paginate($this->perPage);
    }

    #[On('field-created')]
    public function afterCreated($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);

        $field = Field::find($id);
        if (!$field) {
            return;
        }
        $beforeCount = Field::where('title', '<', $field->title)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);

        $this->highlightedFieldId = $field->id;
    }

    #[On('field-updated')]
    public function afterUpdated($id = null): void
    {
        $this->highlightedFieldId = $id;

    }

    #[On('field-deleted')]
    public function afterDeleted(): void
    {
        $fields = $this->fields();
        if ($fields->isEmpty() && $fields->currentPage() > 1) {
            $this->previousPage();
        }
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('رشته های آموزشی')}}</flux:text>

        {{-- Create Component --}}
        <livewire:field.create/>
    </div>
    <flux:separator variant="subtle"/>
    <flux:table :paginate="$this->fields" class="inline">

        <flux:table.columns>

            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('#')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                               wire:click="sort('title')">
                {{__('عنوان رشته')}}
            </flux:table.column>

            <flux:table.column>{{__('استانداردها')}}</flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>

            <flux:table.column>{{ __('عملیات') }}</flux:table.column>
        </flux:table.columns>


        <flux:table.rows>

            @if($highlightedFieldId)
                <div x-data x-init="setTimeout(() => $wire.set('highlightedFieldId', null), 1000)"></div>
            @endif

            @foreach ($this->fields as $field)
                @php($class='')
                @if($highlightedFieldId === $field->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif
                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100" :key="$field->id">
                    <flux:table.cell>{{ $field->id }}</flux:table.cell>
                    <flux:table.cell>{{ $field->title }}</flux:table.cell>

                    <flux:table.cell class="text-center">
                        <flux:badge color="green" size="sm"
                                    inset="top bottom">{{ $field->standards_count }}</flux:badge>
                    </flux:table.cell>


                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $field->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($field->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $field->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($field->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            {{--  Edit Modal Button  --}}
                            <div x-data="{ loading: false }"
                                 @click.prevent="if (loading) return; loading = true; setTimeout(() => loading = false, 400);">
                                <flux:tooltip content="ویرایش رشته" position="bottom">

                                    <flux:icon.pencil-square x-show="!loading" variant="micro"
                                                             class="cursor-pointer size-5 text-yellow-500"
                                                             wire:click="$dispatchTo('field.edit', 'show-edit-modal', { field: {{ $field }} })"
                                    />
                                    <flux:icon.loading x-show="loading" class="size-4 text-yellow-500"/>
                                </flux:tooltip>
                            </div>

                            <div x-data="{ loading: false }"
                                 @@click.prevent="if (loading) return; loading = true; setTimeout(() => loading = false, 500);">
                                <flux:tooltip content="حذف رشته" position="bottom">
                                    <flux:icon.trash x-show="!loading" variant="micro"
                                                     class="cursor-pointer size-5 text-red-500"
                                                     wire:click="$dispatchTo('field.delete', 'show-delete-modal', { field: {{ $field }} })"
                                    />
                                    <flux:icon.loading x-show="loading" class="size-4 text-red-500"/>
                                </flux:tooltip>
                            </div>

                        </div>


                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>


    <livewire:field.edit/>
    <livewire:field.delete/>
</div>
