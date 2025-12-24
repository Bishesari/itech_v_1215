<?php

use App\Models\Province;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public string $sortBy = 'name_fa';
    public string $sortDirection = 'asc';

    public function sort(string $column): void
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
    public function provinces()
    {
        return Province::query()
            ->withCount('cities') // حتی اگر صفر باشد، استان حذف نمی‌شود
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
    }

    public function toggleStatus(int $provinceId): void
    {
        $province = Province::findOrFail($provinceId);

        $province->update([
            'is_active' => !$province->is_active,
        ]);

        $this->dispatch('province-updated');

        Flux::toast(
            heading: 'به‌روزرسانی شد',
            text: 'وضعیت استان با موفقیت تغییر کرد.',
            variant: 'warning'
        );
    }

    #[On('province-created')]
    #[On('province-updated')]
    #[On('province-deleted')]
    public function refreshList(): void
    {
        $this->resetPage();
    }
}; ?>


<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('استان ها')}}</flux:text>
        <livewire:province.create/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->provinces" class="inline">
        <flux:table.columns>
            <flux:table.column>{{__('#')}}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_fa'" :direction="$sortDirection"
                               wire:click="sort('name_fa')">
                {{__('استان')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_en'" :direction="$sortDirection"
                               wire:click="sort('name_en')">{{__('Province')}}</flux:table.column>
            <flux:table.column>{{__('تعداد شهرها')}}</flux:table.column>

            <flux:table.column>{{__('فعال')}}</flux:table.column>


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

            @foreach ($this->provinces as $province)
                <flux:table.row>
                    <flux:table.cell>{{ $province->id }}</flux:table.cell>
                    <flux:table.cell>{{ $province->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $province->name_en }}</flux:table.cell>
                    <flux:table.cell class="text-center">
                        <flux:badge color="green" size="sm"
                                    inset="top bottom">{{ $province->cities_count }}</flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:badge size="sm" color="{{ $province->is_active ? 'green' : 'red' }}">
                                {{ $province->is_active ? 'فعال' : 'غیرفعال' }}
                            </flux:badge>

                            @php
                                $checked = $province->is_active ? 'checked' : null;
                            @endphp
                            <flux:switch :$checked
                                wire:key="province-switch-{{ $province->id }}-{{ $province->is_active }}"
                                wire:click="toggleStatus({{ $province->id }})"
                                wire:loading.attr="disabled"
                            />

                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $province->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($province->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $province->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($province->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>


                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:link href="{{ route('province.show', $province) }}" variant="subtle" size="sm">
                                {{ __('شهرها') }}
                            </flux:link>
                            <livewire:province.edit :$province :key="'province-edit-'.$province->id"/>
                            <livewire:province.delete :$province :key="'province-delete-'.$province->id"/>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
