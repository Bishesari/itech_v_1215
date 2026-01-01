<?php

use App\Models\Branch;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $sortBy = 'id';
    public string $sortDirection = 'desc';

    protected array $sortable = ['code', 'abbr', 'short_name', 'province', 'city', 'credit_balance', 'created_at', 'updated_at'];


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
    #[On('branch-deleted')]
    public function branches()
    {
        return Branch::query()
            ->when($this->sortBy === 'province', function ($query) {
                $query->join('provinces', 'branches.province_id', '=', 'provinces.id')
                    ->orderBy('provinces.name_fa', $this->sortDirection)
                    ->select('branches.*');
            })
            ->when($this->sortBy === 'city', function ($query) {
                $query->join('cities', 'branches.city_id', '=', 'cities.id')
                    ->orderBy('cities.name_fa', $this->sortDirection)
                    ->select('branches.*');
            })
            ->when(!in_array($this->sortBy, ['province', 'city']), function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate(12);
    }

    public function toggleStatus(int $branchId): void
    {
        $branch = Branch::findOrFail($branchId);

        $branch->update([
            'is_active' => !$branch->is_active,
        ]);

        $this->dispatch('branch-updated');

        Flux::toast(
            heading: 'به‌روزرسانی شد',
            text: 'وضعیت شعبه با موفقیت تغییر کرد.',
            variant: 'warning',
            position: 'top right'
        );
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('شعبه ها')}}</flux:text>
        <flux:tooltip content="شعبه جدید" position="left">
            <flux:link href="{{ route('branch.create') }}" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
                {{-- آیکن پلاس --}}
                <flux:icon.plus-circle x-show="!loading" variant="micro" class="size-5 text-blue-500 mr-3"/>
                {{-- لودر --}}
                <flux:icon.loading x-show="loading" class="size-5 text-blue-500 mr-3"/>
            </flux:link>
        </flux:tooltip>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->branches">
        <flux:table.columns>

            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">{{__('#')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'code'" :direction="$sortDirection"
                               wire:click="sort('code')">{{__('کد شعبه')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'abbr'" :direction="$sortDirection"
                               wire:click="sort('abbr')">{{__('نام اختصاری')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'short_name'" :direction="$sortDirection"
                               wire:click="sort('short_name')">{{__('نام کوتاه')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'province'" :direction="$sortDirection"
                               wire:click="sort('province')">{{__('استان')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'city'" :direction="$sortDirection"
                               wire:click="sort('city')">{{__('شهر')}}
            </flux:table.column>

            <flux:table.column>{{__('تلفن')}}</flux:table.column>
            <flux:table.column>{{__('موبایل')}}</flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'credit_balance'" :direction="$sortDirection"
                               wire:click="sort('credit_balance')">{{__('مانده اعتبار')}}
            </flux:table.column>

            <flux:table.column>{{__('فعال')}}</flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">{{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">{{__('زمان ویرایش')}}
            </flux:table.column>

            <flux:table.column>{{ __('عملیات') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>

            @foreach ($this->branches as $branch)
                <flux:table.row>
                    <flux:table.cell>
                        <flux:heading class="flex items-center gap-1">
                            {{$branch->id}}
                            <flux:tooltip toggleable position="left">
                                <flux:button icon="information-circle" size="sm" variant="ghost"
                                             class="cursor-pointer"/>
                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                    <p class="text-justify">{{__('نام کامل: ')}}{{ $branch->full_name }}</p>
                                    <p class="text-justify">{{__('آدرس: ')}}{{ $branch->address }}</p>
                                    <p class="text-justify">{{__('کدپستی: ')}}{{ $branch->postal_code }}</p>
                                </flux:tooltip.content>
                            </flux:tooltip>
                        </flux:heading>
                    </flux:table.cell>
                    <flux:table.cell>{{ $branch->code }}</flux:table.cell>
                    <flux:table.cell>{{ $branch->abbr }}</flux:table.cell>
                    <flux:table.cell>{{ $branch->short_name }}</flux:table.cell>

                    <flux:table.cell>{{ $branch->province->name_fa }}</flux:table.cell>
                    <flux:table.cell>{{ $branch->city->name_fa }}</flux:table.cell>

                    <flux:table.cell>{{ $branch->phone }}</flux:table.cell>
                    <flux:table.cell>{{ $branch->mobile }}</flux:table.cell>
                    <flux:table.cell><span dir="ltr">{{ number_format( $branch->credit_balance,0,"."," / ") }}</span>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:badge size="sm" color="{{ $branch->is_active ? 'green' : 'red' }}">
                                {{ $branch->is_active ? 'فعال' : 'غیرفعال' }}
                            </flux:badge>

                            @php
                                $checked = $branch->is_active ? 'checked' : null;
                            @endphp
                            <flux:switch :$checked
                                         wire:key="province-switch-{{ $branch->id }}-{{ $branch->is_active }}"
                                         wire:click="toggleStatus({{ $branch->id }})"
                                         wire:loading.attr="disabled"
                            />

                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $branch->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($branch->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $branch->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($branch->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            <flux:link href="{{ route('branch.edit', $branch) }}" variant="subtle" wire:navigate
                                       x-data="{ loading: false }" @click="loading = true">
                                <flux:icon.pencil-square variant="micro" x-show="!loading"
                                                         class="size-5 text-yellow-500"/>
                                <flux:icon.loading x-show="loading" class="size-5 text-yellow-500"/>
                            </flux:link>
                            <livewire:branch.delete :branch="$branch" :key="'branch-delete-'.$branch->id"/>

                        </div>
                    </flux:table.cell>

                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
