<?php

use App\Models\Products\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.public')]
class extends Component {
    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    #[Computed]
    public function products()
    {
        return $this->category
            ->products()
            ->whereNull('parent_id')
            ->latest()
            ->get();
    }
}; ?>

<div class="container mx-auto py-8">

    <flux:heading size="xl">
        {{ $category->title }}
    </flux:heading>

    <flux:separator class="my-6"/>

    @if ($this->products->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @foreach ($this->products as $product)
                <flux:card>

                    <flux:heading size="sm">
                        {{ $product->title }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {{ number_format($product->price / 10) }} تومان
                    </flux:text>

                    <a
                        href="{{ route('product.show', $product) }}"
                        class="mt-4 inline-block text-primary"
                    >
                        مشاهده جزئیات
                    </a>

                </flux:card>
            @endforeach

        </div>
    @else
        <flux:callout variant="subtle">
            محصولی در این دسته‌بندی وجود ندارد.
        </flux:callout>
    @endif

</div>


