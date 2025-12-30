<?php

use App\Models\City;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public City $city;

    public string $name_fa = '';
    public string $name_en = '';

    protected function rules(): array
    {
        return [
            'name_fa' => ['required', 'min:2', Rule::unique('cities', 'name_fa')->ignore($this->city)],
            'name_en' => ['required', 'min:2', Rule::unique('cities', 'name_en')->ignore($this->city)],
        ];
    }

    public function mount(): void
    {
        $this->name_fa = $this->city->name_fa;
        $this->name_en = $this->city->name_en;
    }


    public function update_city(): void
    {
        $this->city->update($this->validate());
        $this->modal('edit-city-' . $this->city->id)->close();
        $this->dispatch('city-updated', id: $this->city->id);

        Flux::toast(
            heading: 'تغییرات اعمال شد.',
            text: 'استان ' . $this->name_fa . ' با موفقیت ویرایش شد.',
            variant: 'warning',
            position: 'top right'
        );
    }

}; ?>

<div>
    <flux:tooltip content="ویرایش شهر" position="bottom">
        <flux:icon.pencil-square variant="micro" class="cursor-pointer size-5 text-yellow-500"
                                 x-on:click="$flux.modal('edit-city-{{ $city->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="edit-city-{{ $city->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96" flyout
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش شهر ')}} <span  class="font-bold text-yellow-500">{{ $city->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات شهر را جهت ویرایش را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="update_city" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام استان فارسی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام استان لاتین:')}}" dir="ltr" maxlength="40"
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
