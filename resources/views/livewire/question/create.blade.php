<?php

use App\Models\Option;
use App\Models\Question;
use App\Models\Standard;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public int $standard_id = 0;
    public int $chapter_id = 0;

    public string $text = '';
    public bool $is_final = false;
    public string $difficulty = 'easy';
    public array $options = ['', '', '', ''];
    public array $dir = [false, false, false, false];
    public int $correct = 0;

    public function mount($sid, $cid)
    {
        $this->standard_id = $sid;
        $this->chapter_id = $cid;
    }

    protected function rules(): array
    {
        return [
            'standard_id' => ['required', 'numeric', 'exists:standards,id'],
            'chapter_id' => ['required', 'numeric', 'exists:chapters,id'],
            'text' => ['required', 'string', 'min:3', 'max:1000'],
            'options' => ['required', 'array', 'size:4'],
            'options.*' => ['required', 'string', 'min:1', 'max:500'],
            'correct' => ['required', 'integer', 'min:0', 'max:3'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'is_final' => ['boolean'],
            'dir' => ['required', 'array', 'size:4'],
            'dir.*' => ['boolean'],
        ];
    }

    public function add_question(): void
    {
        $this->validate();

        $question = Question::create([
            'chapter_id' => $this->chapter_id,
            'text' => $this->text,
            'difficulty' => $this->difficulty,
            'is_final' => $this->is_final,
            'assigned_by' => Auth::user()->id,
        ]);

        foreach ($this->options as $index => $text) {
            if ($this->dir[$index]) {
                $dir = 'ltr';
            } else {
                $dir = 'rtl';
            }
            Option::create([
                'question_id' => $question->id,
                'text' => $text,
                'dir' => $dir,
                'is_correct' => $index == $this->correct,
            ]);
        }
        $url = route('question.index', ['sid' => $this->standard_id, 'cid' => $this->chapter_id]);
        redirect($url);
    }
}; ?>


<div>

    <flux:heading size="lg" level="1">
        {{__('بانک سوال')}}
    </flux:heading>
    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>

            <flux:breadcrumbs.item href="{{route('question.index', ['sid' => $standard_id, 'cid' => 0])}}"
                                   wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" class="text-blue-500">
                    @if($standard_id)
                        {{ \App\Models\Standard::find($standard_id)?->name_fa ?? 'نامشخص' }}
                    @else
                        {{__('استاندارد')}}
                    @endif
                </span>
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>

            <flux:breadcrumbs.item href="{{route('question.index', ['sid' => $standard_id, 'cid' => $chapter_id])}}"
                                   wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" class="text-blue-500">
                    @if($chapter_id)
                        {{ \App\Models\Chapter::find($chapter_id)?->title ?? 'نامشخص' }}
                    @else
                        {{__('سرفصل')}}
                    @endif
                </span>
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>

            <flux:breadcrumbs.item>{{__('جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <flux:separator variant="subtle"/>

    <form wire:submit.prevent="add_question" class="grid w-[400px] gap-y-5 mt-5" autocomplete="off" autofocus>


        <!-- Standard select menu... -->
        <flux:select wire:model.live="standard_id" variant="listbox" placeholder="استانداردی انتخاب کنید ..."
                     label="استاندارد" searchable>
            @foreach (\App\Models\Standard::all() as $standard)
                <flux:select.option value="{{$standard->id}}">{{ $standard->name_fa }}</flux:select.option>
            @endforeach
        </flux:select>

        <!-- Chapter select menu... -->
        <flux:select wire:model.live="chapter_id" wire:key="{{ $standard_id }}" variant="listbox"
                     placeholder="سرفصل را انتخاب کنید ..."
                     label="فصل">
            @foreach (\App\Models\Chapter::whereStandardId($standard_id)->get() as $chapter)
                <flux:select.option value="{{$chapter->id}}">{{ $chapter->title }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:textarea rows="4" wire:model="text" label="متن سوال" resize="none"/>

        @foreach ($options as $i => $opt)
            <div>
                <div class="flex justify-between mb-0.5">
                    <flux:label>{{__('گزینه '). $i+1 }}</flux:label>
                    <flux:checkbox wire:model="dir.{{ $i }}" label="متن لاتین (چپ به راست)"/>
                </div>
                <flux:textarea rows="2" wire:model="options.{{ $i }}" resize="none"/>
            </div>
        @endforeach

        <div class="grid grid-cols-2 gap-4">
            <flux:select variant="listbox" wire:model="difficulty" placeholder="انتخاب کنید..." label="سختی سوال"
                         clearable>
                @foreach (\App\Models\Question::CLUSTERS as $key => $label)
                    <flux:select.option value="{{$key}}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select variant="listbox" wire:model="correct" placeholder="انتخاب کنید..." label="گزینه صحیح" clearable>
                @foreach ($options as $i => $opt)
                    <flux:select.option value="{{$i}}">{{__('گزینه '). $i+1 }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex justify-between">
            <flux:button href="{{route('question.index', ['sid'=>$standard_id, 'cid'=>$chapter_id] )}}"
                         variant="primary" color="zinc" wire:navigate
                         size="sm" tabindex="-1">{{__('انصراف')}}</flux:button>
            <flux:field variant="inline">
                <flux:checkbox wire:model="is_final"/>
                <flux:label>{{__('پرتکرار نهایی')}}</flux:label>
            </flux:field>
            <flux:button type="submit" variant="primary" color="sky" size="sm"
                         class="cursor-pointer">{{__('ذخیره')}}</flux:button>
        </div>

    </form>
</div>
