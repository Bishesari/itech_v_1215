<?php

use App\Models\City;
use App\Models\Province;
use App\Models\Role;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    public Province $province;
    use WithPagination;

    public string $sortBy = 'name_fa';
    public string $sortDirection = 'asc';

    public ?int $highlightCityId = null;
    public int $perPage = 10;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function cities()
    {
        return City::query()
            ->where('province_id', $this->province->id)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function toggleStatus(int $cityId): void
    {
        $city = City::findOrFail($cityId);

        $city->update([
            'is_active' => !$city->is_active,
        ]);

        $this->dispatch('city-updated');

        Flux::toast(
            heading: 'به‌روزرسانی شد',
            text: 'وضعیت شهر با موفقیت تغییر کرد.',
            variant: 'warning',
            position: 'top right'
        );
    }

    #[On('city-created')]
    public function cityCreated($id = null): void
    {
        $this->reset('sortBy');
        $this->reset('sortDirection');

        $city = City::find($id);
        if (!$city) {
            return;
        }
        $beforeCount = City::where('name_fa', '<', $city->name_fa)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);
        $this->highlightCityId = $id;
        $this->dispatch('remove-highlight')->self();
    }

    #[On('city-updated')]
    public function cityUpdated($id = null): void
    {
        $this->highlightCityId = $id;
        $this->dispatch('remove-highlight')->self();
    }

    #[On('city-deleted')]
    public function afterDelete(): void
    {
        $cities = $this->cities();
        if ($cities->isEmpty() && $cities->currentPage() > 1) {
            $this->previousPage();
        }
    }

    #[On('remove-highlight')]
    public function removeHighlight(): void
    {
        sleep(2);
        $this->highlightCityId = null;
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('province.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak class="text-blue-500">{{__('استان')}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{$province->name_fa}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <livewire:province.city.create :province_id="$province->id"/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->cities" class="inline">
        <flux:table.columns>
            <flux:table.column>{{__('#')}}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_fa'" :direction="$sortDirection"
                               wire:click="sort('name_fa')">
                {{__('شهر')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_en'" :direction="$sortDirection"
                               wire:click="sort('name_en')">{{__('City')}}</flux:table.column>
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

            @foreach ($this->cities as $city)
                <flux:table.row class="transition duration-500 {{ $highlightCityId === $city->id ? 'bg-green-100 dark:bg-green-900/40' : '' }}">
                    <flux:table.cell>{{ $city->id }}</flux:table.cell>
                    <flux:table.cell>{{ $city->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $city->name_en }}</flux:table.cell>


                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:badge size="sm" color="{{ $city->is_active ? 'green' : 'red' }}">
                                {{ $city->is_active ? 'فعال' : 'غیرفعال' }}
                            </flux:badge>

                            @php
                                $checked = $city->is_active ? 'checked' : null;
                            @endphp
                            <flux:switch :$checked
                                         wire:key="province-switch-{{ $city->id }}-{{ $city->is_active }}"
                                         wire:click="toggleStatus({{ $city->id }})"
                                         wire:loading.attr="disabled"
                            />

                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $city->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($city->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $city->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($city->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>


                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <livewire:province.city.edit :$city :key="'city-edit-'.$city->id"/>
                            <livewire:province.city.delete :$city :key="'city-delete-'.$city->id"/>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


</div>
