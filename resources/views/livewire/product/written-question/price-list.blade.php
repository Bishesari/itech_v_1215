<?php

use App\Models\Product;
use App\Models\WrittenQuestion;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public WrittenQuestion $writtenQuestion;

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
    public function products()
    {
        return Product::query()
            ->where('model_id', $this->writtenQuestion->model_id)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('product-updated')]
    #[On('product-created')]
    public function after($id = null): void
    {
        $this->reset(['sortBy', 'sortDirection']);
        $pr = Product::find($id);

        if (!$pr) {
            return;
        }
        $beforeCount = Product::where('id', '>', $pr->id)->count();
        $page = intdiv($beforeCount, $this->perPage) + 1;
        $this->gotoPage($page);
        $this->highlightedId = $pr->id;
    }

    public function toggleStatus(int $productId): void
    {
        $pr = Product::findOrFail($productId);

        $pr->update([
            'is_active' => !$pr->is_active,
        ]);

        $this->dispatch('product-updated', id: $pr->id);

        Flux::toast(
            heading: 'به‌روزرسانی شد',
            text: 'وضعیت با موفقیت تغییر کرد.',
            variant: 'warning',
            position: 'top right'
        );
    }
}; ?>

<div>

    <flux:heading size="lg" level="1">
        {{__('قیمت گذاری محصول')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('written_question.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak class="text-blue-500">{{__('نمونه سوالات پرتکرار')}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{$writtenQuestion->standard->name_fa}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <livewire:product.create :model_id="$writtenQuestion->model_id"/>
    </div>

    <flux:separator variant="subtle"/>
    @if($this->products->count())
        <flux:table :paginate="$this->products" class="inline">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                                   wire:click="sort('id')">
                    {{__('شناسه')}}
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'price'" :direction="$sortDirection"
                                   wire:click="sort('price')">
                    {{__('قیمت (ریال)')}}
                </flux:table.column>

                <flux:table.column sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection"
                                   wire:click="sort('is_active')">
                    {{__('فعال')}}
                </flux:table.column>

                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                                   wire:click="sort('created_at')">
                    {{__('تاریخ ثبت')}}
                </flux:table.column>

                <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                                   wire:click="sort('updated_at')">
                    {{__('تاریخ ویرایش')}}
                </flux:table.column>

            </flux:table.columns>

            @if($highlightedId)
                <div x-data x-init="setTimeout(() => $wire.set('highlightedId', null), 1000)"></div>
            @endif
            <flux:table.rows>
                @foreach ($this->products as $product)
                    @php
                    $class = '';
                    if ($highlightedId === $product->id){
                        $class='bg-green-100 dark:bg-green-900/40';
                    }
                    @endphp


                    <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                    :key="$product->id">
                        <flux:table.cell align="center">{{ $product->id }}</flux:table.cell>
                        <flux:table.cell align="center">{{number_format($product->price) }}</flux:table.cell>

                        <flux:table.cell>
                            <div class="inline-flex items-center gap-2">
                                <flux:badge size="sm" color="{{ $product->is_active ? 'green' : 'red' }}">
                                    {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                                </flux:badge>

                                @php
                                    $checked = $product->is_active ? 'checked' : null;
                                @endphp
                                <flux:switch :$checked
                                             wire:key="province-switch-{{ $product->id }}-{{ $product->is_active }}"
                                             wire:click="toggleStatus({{ $product->id }})"
                                             wire:loading.attr="disabled" class="cursor-pointer"
                                />

                            </div>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <div class="leading-tight">
                                <div>{{ explode(' ', $product->jalali_created_at)[0] }}</div>
                                <div class="text-xs">
                                    {{ substr($product->jalali_created_at, 11, 5) }}
                                    <span class="text-stone-500">{{ substr($product->jalali_created_at, 17) }}</span>
                                </div>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <div class="leading-tight">
                                <div>{{ explode(' ', $product->jalali_updated_at)[0] }}</div>
                                <div class="text-xs">
                                    {{ substr($product->jalali_updated_at, 11, 5) }}
                                    <span class="text-stone-500">{{ substr($product->jalali_updated_at, 17) }}</span>
                                </div>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>

        </flux:table>
    @else
        <flux:text class="mt-2">
            {{__('هنوز هیچ قیمت گذاری انجام نشده است.')}}
        </flux:text>

    @endif

</div>
