<?php

use App\Models\Chapter;
use App\Models\Standard;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {

    #[Locked]
    public Standard $standard;

    public string $number = '';
    public string $title = '';

    public bool $show_btn = true;

    protected function rules(): array
    {
        return [
            'number' => ['required', 'numeric'],
            'title' => ['required', 'min:2']
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['standard_id'] = $this->standard->id;
        Chapter::create($validated);
        $this->modal('new-chapter')->close();
        $this->dispatch('chapter-created');
        $this->reset_prop();

        Flux::toast(
            heading: 'ثبت شد.',
            text: 'سرفصل  جدید با موفقیت ثبت شد.',
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
    @if($show_btn)
        <flux:tooltip content="سرفصل جدید" position="left">
            <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                                   x-on:click="$flux.modal('new-chapter').show()"/>
        </flux:tooltip>
    @endif
    <flux:modal name="new-chapter" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج سرفصل جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات سرفصل جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">

                <x-my.flt_lbl name="number" label="{{__('شماره فصل:')}}" maxlength="2" dir="ltr"
                              class="tracking-wider font-semibold" autofocus required/>

                <x-my.flt_lbl name="title" label="{{__('عنوان سرفصل:')}}" maxlength="100"
                              class="tracking-wider font-semibold" autofocus required/>
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
