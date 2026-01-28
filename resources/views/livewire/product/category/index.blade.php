<?php

use App\Models\Products\Category;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {

    public string $sortBy = 'title';
    public string $sortDirection = 'asc';

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
    public function categories()
    {
        return Category::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('گروهبندی محصولات')}}</flux:text>
    </div>
    <flux:separator variant="subtle"/>

    <flux:table class="inline">
        <flux:table.columns>

            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')" align="center">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                               wire:click="sort('title')" align="center">
                {{__('عنوان گروه')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection"
                               wire:click="sort('slug')" align="center">
                {{__('شناسه متنی')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'is_subscription'" :direction="$sortDirection"
                               wire:click="sort('is_subscription')">
                {{__('نوع فروش اشتراکی؟')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'is_repeatable'" :direction="$sortDirection"
                               wire:click="sort('is_repeatable')">
                {{__('قابل خرید مجدد')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'has_duration'" :direction="$sortDirection"
                               wire:click="sort('has_duration')">
                {{__('دارای مدت اعتبار')}}
            </flux:table.column>


            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                               wire:click="sort('created_at')">
                {{__('زمان ثبت')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                               wire:click="sort('updated_at')">
                {{__('زمان ویرایش')}}
            </flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->categories as $category)
                <flux:table.row class="dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100" :key="$category->id">
                    <flux:table.cell align="center">{{ $category->id }}</flux:table.cell>

                    <flux:table.cell>{{ $category->title }}</flux:table.cell>
                    <flux:table.cell class="text-left">{{ $category->slug }}</flux:table.cell>

                    <flux:table.cell align="center">{{ $category->is_subscription }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $category->is_repeatable }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $category->has_duration }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $category->jalali_created_at)[0] }}</div>
                            <div class="text-xs">
                                {{ substr($category->jalali_created_at, 11, 5) }}
                                <span class="text-stone-500">{{ substr($category->jalali_created_at, 17) }}</span>
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $category->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">
                            {{ substr($category->jalali_updated_at, 11, 5) }}
                            <span class="text-stone-500">{{ substr($category->jalali_updated_at, 17) }}</span>
                        </div>
                    </flux:table.cell>

                </flux:table.row>
            @endforeach
        </flux:table.rows>



    </flux:table>


</div>
