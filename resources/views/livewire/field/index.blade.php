<?php

use App\Models\Field;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    #[Computed]
    public function fields()
    {
        return Field::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('field-created')]
    public function afterCreated($id = null): void
    {
        $this->reset('sortBy');
        $this->reset('sortDirection');

        $field = Field::find($id);
        if (! $field) {return;}
        $beforeCount = Field::where('id', '>', $field->id)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);
    }



    #[On('field-updated')]
    public function afterUpdated($id = null): void
    {

    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('نقشهای کاربری')}}</flux:text>

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
            @foreach ($this->fields as $field)
                <flux:table.row>
                    <flux:table.cell>{{ $field->id }}</flux:table.cell>
                    <flux:table.cell>{{ $field->title }}</flux:table.cell>

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
                            <flux:tooltip content="ویرایش رشته" position="bottom" >
                                <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                                         wire:click="$dispatchTo('field.edit', 'show-edit-modal', { field: {{ $field }} })"
                                />
                            </flux:tooltip>

                        </div>

                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>


    <livewire:field.edit/>
</div>
