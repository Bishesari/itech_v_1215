<?php

use App\Models\Standard;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public Standard $standard;

    #[On('chapter-created')]
    #[On('chapter-updated')]
    #[On('chapter-deleted')]
    public function afterChange(): void
    {
        $this->dispatch('reloadPage');;
    }

}; ?>

<div>
    <flux:heading size="lg" level="1">
        {{__('اطلاعات پایه')}}
    </flux:heading>

    <div class="inline-flex mt-2 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{route('field.index')}}" wire:navigate x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak
                      class="text-blue-500">{{__('رشته')}} {{'( ' .$standard->field->title .' )'}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>

            <flux:breadcrumbs.item href="{{route('standard.index', ['highlight_id' => $standard->id])}}" wire:navigate
                                   x-data="{ loading: false }"
                                   @click="loading = true">
                <span x-show="!loading" x-cloak class="text-blue-500">{{__('استاندارد')}}</span>
                <flux:icon.loading x-show="loading" x-cloak class="size-5 animate-spin text-blue-500 mr-3"/>
            </flux:breadcrumbs.item>

            <flux:breadcrumbs.item>{{$standard->name_fa}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <livewire:standard.chapter.create :standard="$standard"/>
    </div>
    <flux:separator variant="subtle"/>

    <flux:table class="inline">
        <flux:table.columns>

            <flux:table.column>{{__('#')}}</flux:table.column>
            <flux:table.column>{{__('شماره فصل')}}</flux:table.column>
            <flux:table.column>{{__('عنوان')}}</flux:table.column>
            <flux:table.column>{{__('زمان ثبت')}}</flux:table.column>
            <flux:table.column>{{__('زمان ویرایش')}}</flux:table.column>
            <flux:table.column>{{ __('عملیات') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->standard->chapters as $chapter)
                <flux:table.row class="dark:hover:bg-stone-900/80 transition duration-300 hover:bg-zinc-100">
                    <flux:table.cell class="text-center">{{ $chapter->id }}</flux:table.cell>
                    <flux:table.cell class="text-center">{{ $chapter->number }}</flux:table.cell>
                    <flux:table.cell>{{ $chapter->title }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="leading-tight">
                            <div>{{ explode(' ', $chapter->jalali_created_at)[0] }}</div>
                            <div class="text-xs">{{ substr($chapter->jalali_created_at, 11, 5) }}</div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div>{{ explode(' ', $chapter->jalali_updated_at)[0] }}</div>
                        <div class="text-xs">{{ substr($chapter->jalali_updated_at, 11, 5) }}</div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="inline-flex items-center gap-2">
                            {{--  Edit Modal Button  --}}
                            <div x-data="{ loading: false }"
                                 @click.prevent="if (loading) return; loading = true; setTimeout(() => loading = false, 400);">
                                <flux:tooltip content="ویرایش" position="bottom">

                                    <flux:icon.pencil-square x-show="!loading" variant="micro"
                                                             class="cursor-pointer size-5 text-yellow-500"
                                                             wire:click="$dispatchTo('standard.chapter.edit', 'show-edit-modal', { chapter: {{ $chapter }} })"
                                    />
                                    <flux:icon.loading x-show="loading" class="size-4 text-yellow-500"/>
                                </flux:tooltip>
                            </div>

                            <div x-data="{ loading: false }"
                                 @click.prevent="if (loading) return; loading = true; setTimeout(() => loading = false, 500);">
                                <flux:tooltip content="حذف" position="bottom">
                                    <flux:icon.trash x-show="!loading" variant="micro"
                                                     class="cursor-pointer size-5 text-red-500"
                                                     wire:click="$dispatchTo('standard.chapter.delete', 'show-delete-modal', { chapter: {{ $chapter }} })"
                                    />
                                    <flux:icon.loading x-show="loading" class="size-4 text-red-500"/>
                                </flux:tooltip>
                            </div>

                        </div>

                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <livewire:standard.chapter.edit />
    <livewire:standard.chapter.delete />

</div>
