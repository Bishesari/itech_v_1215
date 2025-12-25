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
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('branch.index')}}">{{__('شعبه')}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{__('جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:separator variant="subtle"/>

    <form wire:submit.prevent="save" class="grid mt-5 w-[350px] gap-y-4" autocomplete="off" autofocus>
        <x-my.flt_lbl name="name_fa" label="{{__('نام شهر فارسی:')}}" maxlength="40"
                      class="tracking-wider font-semibold" autofocus required/>
        <x-my.flt_lbl name="name_en" label="{{__('نام شهر لاتین:')}}" dir="ltr" maxlength="40"
                      class="tracking-wider font-semibold" required/>

        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary" color="teal"
                         class="cursor-pointer">{{__('ثبت')}}</flux:button>
        </div>
    </form>
</div>
