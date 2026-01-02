<?php

use App\Models\Field;
use App\Models\Standard;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {

    public int|string $field_id = '';
    public string $code;
    public string $name_fa;
    public string $name_en;
    public float $nazari_h = 0;
    public float $amali_h = 0;
    public float $karvarzi_h = 0;
    public float $project_h = 0;
    public float $required_h = 0;
    public float $sum_h = 0;

    public int $standard_id = 0;


    protected function rules(): array
    {
        return [
            'field_id' => ['required', 'exists:fields,id'],
            'code' => ['required', 'min:5', Rule::unique('standards', 'code')],
            'name_fa' => ['required', 'min:2'],
            'name_en' => ['required', 'min:2'],
            'nazari_h' => ['required', 'numeric'],
            'amali_h' => ['required', 'numeric'],
            'karvarzi_h' => ['required', 'numeric'],
            'project_h' => ['required', 'numeric'],
            'required_h' => ['required', 'numeric', 'gt:0'],
            'sum_h' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function calc_sum(): void
    {
        $this->validateOnly('nazari_h');
        $this->validateOnly('amali_h');
        $this->validateOnly('karvarzi_h');
        $this->validateOnly('project_h');
        $this->sum_h = $this->nazari_h + $this->amali_h + $this->karvarzi_h + $this->project_h;
    }

    public function save(): void
    {
        $this->calc_sum();
        $validated = $this->validate();
        $standard = Standard::create($validated);
        $this->dispatch('standard-created');
        $this->standard_id = $standard->id;
    }

    #[Computed]
    public function fields()
    {
        return Field::orderBy('title')->get();
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('standard.index', ['highlight_id' => '0'])}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" class="text-blue-500">{{__('استاندارد')}}</span>
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{__('جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:separator variant="subtle"/>

    <form wire:submit.prevent="save" class="grid mt-5 w-[400px] gap-y-4" autocomplete="off" autofocus>
        <x-my.flt_lbl name="code" label="{{__('کد استاندارد:')}}" dir="ltr" class="tracking-widest font-semibold"
                      autofocus required/>
        <flux:select variant="listbox" searchable placeholder="انتخاب رشته آموزشی" wire:model.live="field_id"
                     required>
            @foreach ($this->fields as $field)
                <flux:select.option value="{{ $field->id }}">
                    {{ $field->title }}
                </flux:select.option>
            @endforeach
            <flux:separator class="my-2"/>
            <flux:select.option.create modal="new-field"
                                       class="cursor-pointer">{{__('رشته جدید')}}</flux:select.option>
        </flux:select>

        <x-my.flt_lbl name="name_fa" label="{{__('نام فارسی:')}}" required/>
        <x-my.flt_lbl name="name_en" label="{{__('نام لاتین:')}}" dir="ltr" required/>


        <div class="grid grid-cols-3 gap-2">
            <x-my.flt_lbl name="nazari_h" label="{{__('نظری:')}}" maxlength="4" dir="ltr"
                          class="tracking-widest font-semibold" required/>
            <x-my.flt_lbl name="amali_h" label="{{__('عملی:')}}" maxlength="4" dir="ltr"
                          class="tracking-widest font-semibold" required/>
            <x-my.flt_lbl name="karvarzi_h" label="{{__('کارورزی:')}}" maxlength="4" dir="ltr"
                          class="tracking-widest font-semibold" required/>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <x-my.flt_lbl name="project_h" label="{{__('ساعت پروژه:')}}" maxlength="4" dir="ltr"
                          class="tracking-widest font-semibold" required/>
            <x-my.flt_lbl name="sum_h" label="{{__('مجموع:')}}" dir="ltr" class="tracking-widest font-semibold"
                          disabled/>
            <x-my.flt_lbl name="required_h" label="{{__('موردنیاز:')}}" maxlength="4" dir="ltr"
                          class="tracking-widest font-semibold" required/>
        </div>

        <div class="flex justify-between flex-row-reverse">
            <flux:button type="submit" variant="primary" color="blue"
                         class="cursor-pointer">{{__('ثبت')}}</flux:button>
            <flux:button wire:click="calc_sum" variant="primary" color="indigo"
                         class="cursor-pointer">{{__('محاسبه مجموع')}}</flux:button>
            <flux:button href="{{route('branch.index', ['highlight_id' => '0'])}}" variant="primary" color="zinc" class="w-18"
                         x-data="{ loading: false }" @click="loading = true" wire:navigate>
                <span x-show="!loading">{{__('انصراف')}}</span>
                <flux:icon.loading x-show="loading" class="size-5"/>
            </flux:button>
        </div>

    </form>

    <livewire:field.create :show_btn="false"/>


    <div x-data="{ waiting: false }"
         x-on:standard-created.window="waiting = true; setTimeout(() => { window.location.href = '{{ route('standard.index', ['highlight_id'=>$standard_id]) }}'}, 1500);">
        <!-- Overlay -->
        <div x-show="waiting" x-transition.opacity.duration.300ms x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80">
            <flux:callout icon="loading" color="emerald" class="w-[350px]" inline>
                <flux:callout.heading>{{__('استاندارد جدید با موفقیت درج شد')}}</flux:callout.heading>
                <flux:callout.text>{{__('در حال انتقال به لیست استانداردها ....')}}</flux:callout.text>
            </flux:callout>
        </div>
    </div>
</div>
