<?php


use Livewire\Volt\Component;

new class extends Component {
    public int $standardId;
    public int $chapterId;

    public array $options = ['', '', '', ''];

    public function mount(int $sid, int $cid)
    {
        $this->standardId = $sid;
        $this->chapterId = $cid;
    }


}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('بانک سوال')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('branch.index', ['highlight_id' => '0'])}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" class="text-blue-500">{{__('بانک سوال')}}</span>
                <flux:icon.loading x-show="loading" class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{__('سوال جدید')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:separator variant="subtle"/>

    <form wire:submit="add_question" class="grid mt-5 sm:w-[400px]" autocomplete="off" autofocus>

        <!-- Standard select menu... -->
        <flux:select wire:model.live="standardId" variant="listbox" placeholder="استانداردی انتخاب کنید ..."
                     label="استاندارد" searchable class="mb-5">
            @foreach (\App\Models\Standard::all() as $standard)
                <flux:select.option value="{{$standard->id}}">{{ $standard->name_fa }}</flux:select.option>
            @endforeach
        </flux:select>

        <!-- Chapter select menu... -->
        <flux:select wire:model.live="chapterId" wire:key="{{ $standardId }}" variant="listbox"
                     placeholder="سرفصل را انتخاب کنید ..."
                     label="فصل" class="mb-5">
            @foreach (\App\Models\Chapter::whereStandardId($standardId)->get() as $chapter)
                <flux:select.option value="{{$chapter->id}}">{{ $chapter->title }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:textarea rows="4" wire:model="text" label="متن سوال" resize="none" class="mb-6"/>

        @foreach ($options as $i => $opt)
            <div class="flex justify-between mb-0.5">
                <flux:label>{{__('گزینه '). $i+1 }}</flux:label>
                <flux:checkbox wire:model="dir.{{ $i }}" label="متن لاتین (چپ به راست)"/>
            </div>
            <flux:textarea rows="2" wire:model="options.{{ $i }}" resize="none" class="mb-5"/>
        @endforeach

        <div class="grid grid-cols-2 gap-4 mb-5">
            <flux:select variant="listbox" wire:model="difficulty" placeholder="انتخاب کنید..." label="سختی سوال"
                         clearable>
                @foreach (\App\Models\Question::CLUSTERS as $key => $label)
                    <flux:select.option value="{{$key}}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select variant="listbox" wire:model="correct" placeholder="انتخاب کنید..." label="گزینه صحیح"
                         clearable>
                @foreach ($options as $i => $opt)
                    <flux:select.option value="{{$i}}">{{__('گزینه '). $i+1 }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex justify-between">
            <flux:button href="{{URL::signedRoute('question.create', ['sid'=>$standardId, 'cid'=>$chapterId] )}}"
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
