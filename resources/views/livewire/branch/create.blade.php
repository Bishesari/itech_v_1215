<?php

use App\Models\Branch;
use App\Models\City;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {

    public Branch $branch;
    public string $name_fa = '';
    public string $name_en = '';

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
        City::create($validated);
        $this->modal('new-city')->close();
        $this->dispatch('city-created');

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'شهر جدید با موفقیت ثبت شد.',
            variant: 'success'
        );
    }

    public function reset_prop(): void
    {
        $this->resetExcept('province');
        $this->resetErrorBag();
    }


}; ?>

<div>
    <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج شهر جدید')}}</flux:heading>
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

</div>
