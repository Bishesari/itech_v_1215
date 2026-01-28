<flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate x-data="{ loading: false }"
                   @click="loading = true">
    <span>{{ __('داشبرد') }}</span>
    <flux:badge color="{{session('color')}}" size="sm" class="mr-2">{{__('تازه وارد')}}</flux:badge>
    <flux:icon.loading x-show="loading" class="inline absolute left-2 top-2 size-3.5 text-stone-500"/>
</flux:navlist.item>

<flux:navlist.group :heading="__('آزمونهای کتبی')" class="grid" expandable :expanded="request()->routeIs(['exam.index', 'active.quiz', 'active.midterm'])" >

    <flux:navlist.item icon="user-group" href="{{route('active.quiz')}}" :current="request()->routeIs('active.quiz')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('کوئیزهای کلاسی') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>
    <flux:navlist.item icon="user-group" href="{{route('active.midterm')}}" :current="request()->routeIs('active.midterm')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('میانترم') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

</flux:navlist.group>
