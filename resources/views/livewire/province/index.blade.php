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

    public ?int $deletingProvinceId = null;

    public ?int $highlightProvinceId = null;

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function provinces()
    {
        return Province::query()
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->withCount('cities')
            ->paginate(10);
    }

    #[Computed]
    public function provinceToDelete()
    {
        return $this->deletingProvinceId ? Province::find($this->deletingProvinceId) : null;
    }

    public function confirmDelete($id): void
    {
        $this->deletingProvinceId = $id;
        $this->modal('confirm')->show();
    }
    public function deleteProvince(): void
    {
        $province = $this->provinceToDelete();

        $province->delete();
        $this->modal('confirm')->close();
        // ریست کردن متغیر
        $this->deletingProvinceId = null;

        $this->dispatch('province-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'استان با موفقیت حذف شد.',
            variant: 'danger',
            position: 'top right'
        );
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
            variant: 'warning',
            position: 'top right'
        );
    }

    #[On('province-created')]
    public function provinceCreated($id = null): void
    {
        $this->highlightProvinceId = $id;
        $this->resetPage();
        // پاک‌سازی خودکار
        $this->dispatch('remove-highlight')->self();
    }

    #[On('province-updated')]
    public function provinceUpdated($id = null): void
    {
        $this->highlightProvinceId = $id;
        $this->dispatch('$refresh');
        $this->dispatch('remove-highlight')->self();
    }

    #[On('province-deleted')]
    public function afterDelete(): void
    {
        $province = $this->provinces();
        if ($province->isEmpty() && $province->currentPage() > 1) {
            $this->previousPage();
        }
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
                <flux:table.row wire:key="province-row-{{ $province->id }}" class="transition duration-500 {{ $highlightProvinceId === $province->id ? 'bg-green-100 dark:bg-green-900/40' : '' }}">
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
                            <flux:link href="{{ route('province.show', $province) }}" variant="subtle" wire:navigate x-data="{ loading: false }" @click="loading = true">
                                <span x-show="!loading" class="text-blue-500">{{ __('شهرها') }}</span>
                                <flux:icon.loading x-show="loading" class="size-5 text-blue-500 mr-3"/>
                            </flux:link>

                            <livewire:province.edit :province="$province" :key="'province-edit-'.$province->id"/>

                            <flux:tooltip content="حذف استان" position="bottom">
                                <div class="inline-block">
                                    {{-- حالت عادی: نمایش آیکون سطل آشغال --}}
                                    {{-- وقتی روی confirmDelete با این ID خاص کلیک شد، این مخفی شود --}}
                                    <div wire:loading.remove wire:target="confirmDelete({{ $province->id }})">
                                        <flux:icon.trash
                                            variant="micro"
                                            class="cursor-pointer size-5 text-red-500 dark:text-red-400"
                                            wire:click="confirmDelete({{ $province->id }})"
                                        />
                                    </div>

                                    {{-- حالت لودینگ: نمایش آیکون چرخنده --}}
                                    {{-- فقط وقتی نمایش داده شود که confirmDelete با این ID خاص صدا زده شده --}}
                                    <div wire:loading wire:target="confirmDelete({{ $province->id }})">
                                        <flux:icon.loading class="size-5 text-red-500 dark:text-red-400" />
                                    </div>
                                </div>
                            </flux:tooltip>

                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


    <flux:modal name="confirm" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('حذف استان ')}} <span
                        class="font-bold text-red-500 dark:text-red-400">{{$this->provinceToDelete?->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('با تایید اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <div class="flex gap-2">
                {{-- دکمه تایید با لودینگ --}}
                <flux:button wire:click="deleteProvince" variant="primary" color="red" size="sm" class="flex-1">
                    <span wire:loading.remove wire:target="deleteProvince">{{__('تایید حذف')}}</span>
                    <span wire:loading wire:target="deleteProvince">{{__('در حال حذف...')}}</span>
                </flux:button>

                {{-- دکمه انصراف --}}
                <flux:button x-on:click="$flux.modal('confirm').close()" variant="ghost" size="sm" class="flex-1">
                    {{__('انصراف')}}
                </flux:button>
            </div>
        </div>
    </flux:modal>


</div>
