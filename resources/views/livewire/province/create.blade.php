<?php

use App\Models\Province;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public string $name_fa = '';
    public string $name_en = '';

    public bool $show_btn = true;

    protected function rules(): array
    {
        return [
            'name_fa' => ['required', 'min:2', Rule::unique('provinces', 'name_fa')],
            'name_en' => ['required', 'min:2', Rule::unique('provinces', 'name_en')],
        ];
    }


    public function save(): void
    {
        $province = Province::create($this->validate());
        $this->modal('new-province')->close();
        $this->dispatch('province-created', id: $province->id);

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'استان جدید با موفقیت ثبت شد.',
            variant: 'success',
            position: 'top right'
        );
    }

    public function reset_prop(): void
    {
        $this->resetExcept('show_btn');
        $this->resetErrorBag();
    }

}; ?>

<div>
    @if($show_btn)
        <flux:tooltip content="استان جدید" position="left">
            <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                                   x-on:click="$flux.modal('new-province').show()"/>
        </flux:tooltip>
    @endif

    <flux:modal name="new-province" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج استان جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات استان جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام استان فارسی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام استان لاتین:')}}" dir="ltr" maxlength="40"
                              class="tracking-wider font-semibold" required/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
