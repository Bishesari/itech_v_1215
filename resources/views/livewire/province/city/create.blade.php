<?php

use App\Models\City;
use App\Models\Province;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public $province_id;
    public Province $province;

    public bool $show_btn = true;


    public string $name_fa = '';
    public string $name_en = '';

    public function mount($province_id = null): void
    {
        $this->province_id = $province_id;
        $this->province = Province::find($province_id);
    }
    protected function rules(): array
    {
        return [
            'name_fa' => ['required', 'min:2', Rule::unique('cities', 'name_fa')],
            'name_en' => ['required', 'min:2', Rule::unique('cities', 'name_en')],
        ];
    }
    public function save(): void
    {
        $validated = $this->validate();
        $validated['province_id'] = $this->province->id;

        $city=City::create($validated);
        $this->modal('new-city')->close();
        $this->dispatch('city-created', id: $city->id);

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'شهر جدید با موفقیت ثبت شد.',
            variant: 'success',
            position: 'top right'
        );
    }

    public function reset_prop(): void
    {
        $this->reset('name_fa');
        $this->reset('name_en');
        $this->resetErrorBag();
    }


}; ?>

<div>
    @if($show_btn)
        <flux:tooltip content="شهر جدید" position="left">
            <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                                   x-on:click="$flux.modal('new-city').show()"/>
        </flux:tooltip>
    @endif

    <flux:modal name="new-city" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{__('درج شهر جدید برای استان')}}
                    <span class="font-bold text-blue-500">{{ $province->name_fa }}</span>

                </flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات شهر جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <x-my.flt_lbl name="name_fa" label="{{__('نام شهر فارسی:')}}" maxlength="40"
                              class="tracking-wider font-semibold" autofocus required/>
                <x-my.flt_lbl name="name_en" label="{{__('نام شهر لاتین:')}}" dir="ltr" maxlength="40"
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
