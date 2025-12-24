<?php

use App\Models\Province;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public Province $province;

    public string $name_fa = '';
    public string $name_en = '';

    protected function rules(): array
    {
        return [
            'name_fa' => [
                'required',
                'min:2',
                Rule::unique('provinces', 'name_fa')->ignore($this->province),
            ],
            'name_en' => [
                'required',
                'min:2',
                Rule::unique('provinces', 'name_en')->ignore($this->province),
            ],
        ];
    }

    public function mount(): void
    {
        $this->name_fa = $this->province->name_fa;
        $this->name_en = $this->province->name_en;
    }


    public function update_province(): void
    {
        $this->province->update($this->validate());
        $this->modal('edit-province-' . $this->province->id)->close();
        $this->dispatch('province-updated');

        Flux::toast(
            heading: 'تغییرات اعمال شد.',
            text: 'استان '. $this->name_fa .' با موفقیت ویرایش شد.',
            variant: 'warning'
        );
    }

}; ?>

<div>
    <flux:tooltip content="ویرایش استان" position="bottom">
        <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                 x-on:click="$flux.modal('edit-province-{{ $province->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="edit-province-{{ $province->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش استان ')}} <span
                        class="font-bold text-yellow-500">{{ $province->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات استان را جهت ویرایش وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="update_province" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام شهر فارسی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام شهر لاتین:')}}" dir="ltr" maxlength="40"
                              class="tracking-wider font-semibold" required/>

                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="yellow" size="sm"
                                 class="cursor-pointer">{{__('ویرایش')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
