<?php

use App\Models\Product;
use App\Models\Question;
use App\Models\Standard;
use Livewire\Volt\Component;

new class extends Component {
    public Product $product;
    public $questions;
    public Standard $standard;

    public function mount(Product $product)
    {

        $this->product = $product;
        $standard = $product->wq->standard;
        $this->standard = $standard;

        $this->questions = Question::whereIn('chapter_id', $standard->chapters->pluck('id'))
            ->where('is_final', true)
            ->with('options') // بارگذاری گزینه‌ها
            ->orderBy('text')
            ->get();
    }

}; ?>


<div>
    <flux:heading size="lg" class="text-center relative">
        {{ __('نمونه سوالات پرتکرار آزمون کتبی فنی و حرفه ای') }}
    </flux:heading>

    <flux:heading size="lg" class="text-center mt-2">{{$standard->name_fa . ' ( '. $standard->field->title .' ) '}}</flux:heading>
    <flux:heading size="lg" class="text-center mt-2 tracking-widest">{{'کداستاندارد: ' . $standard->code . ' ( '. $standard->sum_h .' ساعت ) '}}</flux:heading>
    <flux:separator class="mt-5 mb-5"/>

    <flux:accordion transition exclusive>
        @php($i=1)
        @foreach($questions as $q)
            <flux:accordion.item>
                <flux:accordion.heading>
                    <span>{{ $i++ }} - {{ $q->text }}</span>
                    <span class="text-gray-500">{{'(' . $q->id.'#)'}}</span>
                </flux:accordion.heading>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-1">
                    @foreach($q->options as $o)
                        @if($o->is_correct)
                            @php($var = 'success')
                            @php($icon = 'check-circle')
                        @else
                            @php($var = 'secondary')
                            @php($icon = '')
                        @endif
                        <flux:accordion.content>
                            <flux:callout variant="{{$var}}" heading="{!! $o->text !!}" dir="{{$o->dir}}"
                                          icon='{{$icon}}'/>
                        </flux:accordion.content>
                    @endforeach
                </div>
            </flux:accordion.item>
        @endforeach
    </flux:accordion>
</div>
