<?php

use App\Models\Product;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {
    public $model_id;

    public string $price;

    public function mount($model_id = null): void
    {
        $this->model_id = $model_id;
    }

    protected function rules(): array
    {
        return [
            'price' => ['required', 'numeric'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['model_id'] = $this->model_id;

        $product = Product::create($validated);
        $this->modal('new-price')->close();
        $this->dispatch('product-created', id: $product->id);

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'قیمت جدید با موفقیت ثبت شد.',
            variant: 'success',
            position: 'top right'
        );
    }

    public function reset_prop(): void
    {
        $this->reset('price');
        $this->resetErrorBag();
    }
}; ?>

<div>
    <flux:tooltip content="قیمت گذاری جدید" position="left">
        <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                               x-on:click="$flux.modal('new-price').show()"/>
    </flux:tooltip>

    <flux:modal name="new-price" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{__('درج قیمت جدید')}}
                    <span class="font-bold text-blue-500">{{ $model_id }}</span>

                </flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات قیمت جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="price" label="{{__('قیمت(ریال):')}}" dir="ltr" maxlength="15"
                              class="tracking-wider font-semibold" required autofocus/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
