<?php

use App\Models\Branch;
use App\Models\City;
use App\Models\Province;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public string $code = '';
    public string $abbr = '';
    public string $short_name = '';
    public string $full_name = '';

    public int|string $province_id = '';
    public int|string $city_id = '';

    public string $address = '';
    public string $postal_code = '';
    public string $phone = '';
    public string $mobile = '';

    protected function rules(): array
    {
        return [
            'code' => ['required', 'size:7', Rule::unique('branches', 'code')],
            'abbr' => ['required', 'size:3', Rule::unique('branches', 'abbr')],
            'short_name' => ['required', 'min:2'],
            'full_name' => ['required', 'min:3'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'min:10'],
            'postal_code' => ['required', 'digits:10'],
            'phone' => ['required', 'digits:11', 'starts_with:0'],
            'mobile' => ['required', 'digits:11', 'starts_with:09'],
        ];
    }

    public function save(): void
    {
        $this->code = strtoupper($this->code);
        $this->abbr = strtoupper($this->abbr);

        $validated = $this->validate();

        Branch::create($validated);

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'شعبه جدید با موفقیت ثبت شد.',
            variant: 'success'
        );
        $this->dispatch('branch-created');
    }

    #[On('province-created')]
    #[Computed]
    public function provinces()
    {
        return Province::orderBy('name_fa')->get();
    }

    #[On('city-created')]
    #[Computed]
    public function cities()
    {
        if (!$this->province_id) {
            return collect();
        }

        return City::where('province_id', $this->province_id)
            ->orderBy('name_fa')
            ->get();
    }

    public function updatedProvinceId(): void
    {
        $this->reset('city_id');
    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('branch.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" class="text-blue-500">{{__('شعبه')}}</span>
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{__('جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:separator variant="subtle"/>



    <form wire:submit.prevent="save" class="grid mt-5 w-[400px] gap-y-4" autocomplete="off" autofocus>
        <div class="grid grid-cols-2 gap-2">
            <x-my.flt_lbl name="code" label="{{__('کد شعبه:')}}" dir="ltr" maxlength="7"
                          class="tracking-wider font-semibold" autofocus required/>
            <x-my.flt_lbl name="abbr" label="{{__('نام اختصاری:')}}" maxlength="3" class="font-semibold" required/>
        </div>

        <x-my.flt_lbl name="short_name" label="{{__('نام کوتاه:')}}" maxlength="30" class="font-semibold" required/>
        <x-my.flt_lbl name="full_name" label="{{__('نام کامل:')}}" maxlength="30" class="font-semibold" required/>

        <div class="grid grid-cols-2 gap-2">
            <flux:select variant="listbox" searchable placeholder="انتخاب استان" wire:model.live="province_id" required>
                @foreach ($this->provinces as $province)
                    <flux:select.option value="{{ $province->id }}">
                        {{ $province->name_fa }}
                    </flux:select.option>
                @endforeach

                    <flux:separator class="my-2" />
                    <flux:select.option.create modal="new-province"
                                               class="cursor-pointer">{{__('استان جدید')}}</flux:select.option>
            </flux:select>

            <div class="relative">
                <flux:select variant="listbox" searchable placeholder="انتخاب شهر" wire:model.live="city_id"
                             :disabled="!$province_id" required>
                    @forelse ($this->cities as $city)
                        <flux:select.option value="{{ $city->id }}">
                            {{ $city->name_fa }}
                        </flux:select.option>
                    @empty
                        <flux:select.option disabled>{{__('شهری برای این استان ثبت نشده است.')}}</flux:select.option>
                    @endforelse
                        <flux:separator class="my-2" />
                        <flux:select.option.create modal="new-city"
                                                   class="cursor-pointer">{{__('شهر جدید')}}</flux:select.option>
                </flux:select>

                <div wire:loading wire:target="province_id" class="absolute left-8 top-3">
                    <flux:icon.loading variant="micro" class="text-blue-500 dark:text-blue-300"/>
                </div>
            </div>
        </div>

        <x-my.flt_lbl name="address" label="{{__('آدرس:')}}" maxlength="150" required/>
        <x-my.flt_lbl name="postal_code" label="{{__('کدپستی:')}}" dir="ltr" maxlength="10"
                      class="tracking-wider font-semibold" required/>
        <x-my.flt_lbl name="phone" label="{{__('تلفن:')}}" dir="ltr" maxlength="11" class="tracking-wider font-semibold"
                      required/>
        <x-my.flt_lbl name="mobile" label="{{__('موبایل:')}}" dir="ltr" maxlength="11"
                      class="tracking-wider font-semibold" required/>


        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary" color="teal" class="cursor-pointer">{{__('ثبت')}}</flux:button>
        </div>
    </form>

    <livewire:province.create :show_btn="false"/>

    @if($province_id)
        <livewire:province.city.create :province_id="$province_id" :show_btn="false" wire:key="city-create-{{ $province_id }}"/>
    @endif




    <div x-data="{ waiting: false }"
         x-on:branch-created.window="waiting = true; setTimeout(() => { window.location.href = '{{ route('branch.index') }}'}, 1000);">
        <!-- Overlay -->
        <div x-show="waiting" x-transition.opacity.duration.300ms x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/80">
            <flux:callout icon="loading" color="emerald" class="w-[350px]" inline>
                <flux:callout.heading>{{__('با موفقیت انجام شد.')}}</flux:callout.heading>
                <flux:callout.text>{{__('در حال انتقال به لیست شعبه ها ....')}}</flux:callout.text>
            </flux:callout>
        </div>
    </div>
</div>
