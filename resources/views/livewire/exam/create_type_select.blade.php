<?php

use Livewire\Volt\Component;

new class extends Component {

    public string $exam_type = '';

    public function set_exam_type($exam_type): void
    {
        $this->exam_type = $exam_type;
    }

    public function reset_type(): void
    {
        $this->reset('exam_type');
    }

    public function create_redirect(): void
    {
        $this->redirectRoute($this->exam_type . '.create');
    }
}; ?>

<div>
    <flux:tooltip content="آزمون جدید" position="left">
        <flux:icon.plus-circle variant="micro" class="cursor-pointer size-5 text-blue-500 mr-4"
                               x-on:click="$flux.modal('exam-type-select').show()"/>
    </flux:tooltip>
    <flux:modal name="exam-type-select" :show="$errors->isNotEmpty()" focusable class="md:w-96"
                :dismissible="false" @close="reset_type">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج آزمون جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('نوع آزمون را انتخاب کنید.')}}</flux:text>
            </div>
            <flux:separator variant="subtle"/>

            @foreach (\App\Models\Exam::CLUSTERS as $key => $label)
                <flux:callout class="cursor-pointer" wire:click="set_exam_type('{{ $key }}')"
                              color="{{ $exam_type == $key ? 'green'  : 'zinc' }}"
                >
                    <flux:callout.heading>
                        {{$label}}
                    </flux:callout.heading>
                </flux:callout>
            @endforeach

            @if($exam_type == '')
                @php($dis = true)
                @php($col = 'zinc')
            @else
                @php($dis = false)
                @php($col = 'indigo')
            @endif
            <flux:button wire:click="create_redirect" variant="primary" color="{{$col}}" :disabled=$dis
                         class="cursor-pointer w-full py-2 text-sm font-medium">
                <span wire:loading.remove wire:target="set_exam_type">{{ __('ادامه') }}</span>
                <flux:icon.loading wire:loading wire:target="set_exam_type" class="inline-block"/>
            </flux:button>


        </div>
    </flux:modal>
</div>
