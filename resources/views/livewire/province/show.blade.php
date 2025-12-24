<?php

use App\Models\City;
use App\Models\Province;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    public Province $province;

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
    public function cities()
    {
        return City::query()
            ->where('province_id', $this->province->id)
            ->orderBy($this->sortBy ?? 'name_fa', $this->sortDirection ?? 'asc')
            ->paginate(12);
    }

    #[On('city-created')]
    #[On('city-updated')]
    #[On('city-deleted')]
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
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('province.index')}}">{{__('استان')}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{$province->name_fa}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>


        <livewire:province.city.create :$province/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->cities" class="inline">
        <flux:table.columns>
            <flux:table.column>{{__('#')}}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_fa'" :direction="$sortDirection"
                               wire:click="sort('name_fa')">
                {{__('استان')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name_en'" :direction="$sortDirection"
                               wire:click="sort('name_en')">{{__('Province')}}</flux:table.column>
            <flux:table.column>{{__('تعداد شهرها')}}</flux:table.column>

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
                <flux:table.row>
                    <flux:table.cell>{{ $city->id }}</flux:table.cell>
                    <flux:table.cell>{{ $city->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $city->name_en }}</flux:table.cell>
                    <flux:table.cell class="text-center">
                        <flux:badge color="green" size="sm"
                                    inset="top bottom">{{ $city->cities_count }}</flux:badge>
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
