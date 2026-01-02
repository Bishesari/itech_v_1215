<flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate x-data="{ loading: false }"
                   @click="loading = true">
    <span>{{ __('داشبرد') }}</span>
    <span class="text-xs text-violet-600">{{__(' - ( بازاریاب )')}}</span>
    <flux:icon.loading x-show="loading" class="inline absolute left-2 size-3.5 text-stone-500"/>
</flux:navlist.item>
