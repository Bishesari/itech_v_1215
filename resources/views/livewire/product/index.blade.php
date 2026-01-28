<?php

use App\Models\Products\Product;
use Livewire\Attributes\Computed;
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
    public function products()
    {
        return Product::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:text>{{__('لیست محصولات')}}</flux:text>
        <livewire:user.create/>
    </div>

    <flux:separator variant="subtle"/>

    <flux:table :paginate="$this->products" class="inline">
        <flux:table.columns>
            <flux:table.column align="center" sortable :sorted="$sortBy === 'id'" :direction="$sortDirection"
                               wire:click="sort('id')">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column align="center">{{__('گروه')}}</flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                               wire:click="sort('title')">
                {{__('عنوان')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'price'" :direction="$sortDirection"
                               wire:click="sort('price')">
                {{__('قیمت (ریال)')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'duration_days'" :direction="$sortDirection"
                               wire:click="sort('duration_days')">
                {{__('روزهای معتبر')}}
            </flux:table.column>

        </flux:table.columns>

        @if($highlightedId)
            <div x-data x-init="setTimeout(() => $wire.set('highlightedId', null), 2000)"></div>
        @endif

        <flux:table.rows>
            @foreach ($this->products as $product)
                @php($class='')

                @if($highlightedId === $product->id)
                    @php($class='bg-green-100 dark:bg-green-900/40')
                @endif
                <flux:table.row class="{{$class}} dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100"
                                :key="$product->id">
                    <flux:table.cell>{{ $product->id }}</flux:table.cell>
                    <flux:table.cell>{{ $product->category->title }}</flux:table.cell>
                    <flux:table.cell>{{ $product->title }}</flux:table.cell>
                    <flux:table.cell class="tracking-wider">{{ number_format($product->price) }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $product->duration_days}}</flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
</div>
