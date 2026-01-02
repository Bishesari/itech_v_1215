<flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate x-data="{ loading: false }"
                   @click="loading = true">
    <span>{{ __('داشبرد') }}</span>
    <span class="text-xs text-red-600">{{__(' - ( سوپر ادمین )')}}</span>
    <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
</flux:navlist.item>

<flux:navlist.group :heading="__('اطلاعات پایه')" class="grid" expandable :expanded="request()->routeIs(['role.index', 'province.index', 'branch.index', 'field.index', 'standard.index'])" >

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

</flux:navlist.group>
