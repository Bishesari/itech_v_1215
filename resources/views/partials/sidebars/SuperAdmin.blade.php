<flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate x-data="{ loading: false }"
                   @click="loading = true">
    <span>{{ __('داشبرد') }}</span>
    <flux:badge color="{{session('color')}}" size="sm" class="mr-2">{{__('سوپر ادمین')}}</flux:badge>
    <flux:icon.loading x-show="loading" class="inline absolute left-2 top-2 size-3.5 text-stone-500"/>
</flux:navlist.item>

<flux:navlist.group :heading="__('اطلاعات پایه')" class="grid" expandable :expanded="request()->routeIs(['role.index', 'province.index', 'branch.index', 'field.index', 'standard.index', 'user.index'])" >

    <flux:navlist.item icon="user-group" :href="route('role.index')" :current="request()->routeIs('role.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('نقشهای کاربری') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('province.index')" :current="request()->routeIs('province.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('استانها') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>


    <flux:navlist.item icon="user-group" :href="route('branch.index', ['highlight_id'=>0])" :current="request()->routeIs('branch.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('لیست شعب') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('field.index')" :current="request()->routeIs('field.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('رشته های آموزشی') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('standard.index', ['highlight_id'=>0])" :current="request()->routeIs('standard.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('استانداردها') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

    <flux:navlist.item icon="user-group" :href="route('user.index')" :current="request()->routeIs('user.index')" wire:navigate x-data="{ loading: false }"
                       @click="loading = true">
        <span>{{ __('کاربران آموزشگاه') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
    </flux:navlist.item>

</flux:navlist.group>

<flux:navlist.group :heading="__('بانک سوال')" class="grid" expandable :expanded="request()->routeIs(['question.index', 'question.create'])" >
    <flux:navlist.item icon="user-group" href="{{route('question.index', ['sid'=>0, 'cid'=>0] )}}" :current="request()->routeIs('question.index')" wire:navigate>{{ __('کل سوالات') }}</flux:navlist.item>
    <flux:navlist.item icon="user-group" href="{{route('question.create', ['sid'=>0, 'cid'=>0] )}}" :current="request()->routeIs('question.create')" wire:navigate>{{ __('درج سوال') }}</flux:navlist.item>
</flux:navlist.group>
