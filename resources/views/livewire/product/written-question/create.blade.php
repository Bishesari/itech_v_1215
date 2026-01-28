<?php

use App\Models\Products\Product;
use App\Models\Products\WrittenQuestionProduct;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public string $standard_id;

    protected function rules(): array
    {
        return [
            'standard_id' => ['required', 'numeric', 'exists:standards,id', 'unique:written_questions'],
        ];
    }


    public function save(): void
    {
        $this->validate();
        $wq = WrittenQuestionProduct::create([
            'standard_id' => $this->standard_id,
        ]);
        $wq->update([
            'model_id' => 'WQ_'.$wq->id
        ]);

        $this->dispatch('wq-created', id: $wq->id);

        $this->modal('new-wq')->close();

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'رشته جدید با موفقیت ثبت شد.',
            variant: 'success',
            position: 'top right'
        );
    }

    public function reset_prop(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

}; ?>

<div>
    <flux:tooltip content="جدید" position="left">
        <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                               x-on:click="$flux.modal('new-wq').show()"/>
    </flux:tooltip>

    <flux:modal name="new-wq" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج محصول نمونه سوالات پرتکرار جدید')}}</flux:heading>
                <flux:text
                    class="mt-2">{{__('استانداردی را که می خواهید نمونه سوالاتش را به عنوان محصول برای فروش ثبت کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <!-- Standard select menu... -->
                <flux:select wire:model.live="standard_id" variant="listbox" placeholder="استانداردی انتخاب کنید ..."
                            searchable>
                    @foreach (\App\Models\Standard::all() as $standard)
                        <flux:select.option value="{{$standard->id}}">{{ $standard->name_fa }}</flux:select.option>
                    @endforeach
                </flux:select>


                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
