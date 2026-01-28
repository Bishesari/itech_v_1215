<?php

use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Product;

new
#[Layout('components.layouts.public')]
class extends Component {

    public Product $product;
    public bool $hasAccess = false;

    public function mount(Product $product): void
    {
        $this->product = $product->load([
            'category',
            'standard.chapters.questions.options'
        ]);

        $this->hasAccess = $this->product->hasAccess();
    }

    public function checkout()
    {

        if ($this->hasAccess) {
            Flux::toast(
                heading: 'دسترسی فعال',
                text: 'شما قبلاً این محصول را خریداری کرده‌اید.',
                variant: 'info'
            );
            return;
        }

        // جلوگیری از ساخت سفارش pending تکراری
        $existingOrder = auth()->user()->orders()
            ->where('product_id', $this->product->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingOrder) {
            return redirect()->route('payment.start', $existingOrder);
        }

        $order = auth()->user()->orders()->create([
            'product_id' => $this->product->id,
            'amount'     => $this->product->price,
            'status'     => 'pending',
        ]);

        return redirect()->route('payment.start', $order);
    }
};

?>


<div class="container mx-auto py-8">

    <flux:heading size="xl">{{ $product->title }}</flux:heading>

    <flux:text class="mt-2 text-zinc-600">
        دسته‌بندی: {{ $product->category->title }}
    </flux:text>

    <flux:separator class="my-6"/>

    {{-- قیمت --}}
    <flux:text class="text-lg font-semibold">
        {{ number_format($product->price / 10) }} تومان
    </flux:text>

    {{-- مدت زمان --}}
    @if($product->duration_days)
        <flux:text class="mt-1 text-sm text-zinc-500">
            مدت اعتبار: {{ $product->duration_days }} روز
        </flux:text>
    @endif

    <flux:separator class="my-8"/>

    @if ($hasAccess)
        <flux:accordion transition exclusive>
            @php($i=1)
            @forelse ($this->questions as $q)
                <flux:accordion.item>
                    <flux:accordion.heading>
                        <span>{{ $i++ }} - {{ $q->text }}</span>
                        <span class="text-gray-500">{{'(' . $q->id.'#)'}}</span>
                    </flux:accordion.heading>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-1">
                        @foreach($q->options as $o)
                            @php($var = $o->is_correct ? 'success' : 'secondary')
                            @php($icon = $o->is_correct ? 'check-circle' : '')
                            <flux:accordion.content>
                                <flux:callout
                                    variant="{{ $var }}"
                                    heading="{!! $o->text ?? '' !!}"
                                    dir="{{ $o->dir ?? 'ltr' }}"
                                    icon="{{ $icon }}"
                                />
                            </flux:accordion.content>
                        @endforeach
                    </div>
                </flux:accordion.item>
            @empty
                <flux:accordion.item>
                    {{__('سوالی برای این محصول ثبت نشده است.')}}
                </flux:accordion.item>
            @endforelse
        </flux:accordion>


    @else
        <flux:callout variant="warning" class="flex flex-col items-center gap-2">
    <span class="text-center">
        @if(!auth()->check())
            برای مشاهده سوالات و خرید، ابتدا وارد حساب کاربری شوید.
        @else
            برای مشاهده سوالات، ابتدا باید این محصول را خریداری کنید.
        @endif
    </span>

            @auth
                <flux:button wire:click="checkout" variant="primary" color="teal">
                    خرید محصول
                </flux:button>
            @endauth
        </flux:callout>


    @endif
</div>

