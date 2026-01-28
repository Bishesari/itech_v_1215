<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" dir="rtl">
<head>
    @include('partials.head')
</head>
@php
    use App\Models\Role;
    use App\Models\Branch;
    $activeRole = Role::find(session('active_role_id'));
    $activeBranch = Branch::find(session('active_branch_id'));


@endphp
<body class="min-h-screen bg-white dark:bg-zinc-800">

<flux:sidebar sticky stashable collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

    <flux:sidebar.header>
        <flux:sidebar.brand href="{{route('home')}}">
            <x-slot name="logo" class="size-16">
                <x-logo class="text-zinc-700 dark:text-zinc-300"/>
            </x-slot>
        </flux:sidebar.brand>
         @if ($activeBranch)
            <flux:badge>{{$activeBranch->short_name}}</flux:badge>
         @endif
        <flux:sidebar.collapse class="lg:hidden in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"/>
    </flux:sidebar.header>



    @if ($activeRole)
        @includeIf('partials.sidebars.' . $activeRole->name_en)
    @else
        <flux:navlist.item icon="user-group" :href="route('select_role')" wire:navigate x-data="{ loading: false }" @click="loading = true">
            <span>{{ __('انتخاب نقش') }}</span>
            <flux:icon.loading x-show="loading" class="inline absolute left-2 top-2 size-3.5 text-stone-500"/>
        </flux:navlist.item>
    @endif




    <flux:spacer />

    <!-- Desktop User Menu -->
    <flux:dropdown class="hidden lg:block" position="bottom" align="start">
        <flux:profile
            :name="auth()->user()->full_fa_name()"
            :initials="auth()->user()->initials()"
            icon:trailing="chevrons-up-down"
            data-test="sidebar-menu-button"
        />

        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->full_fa_name() }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->user_name }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <flux:menu.radio.group>
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('تنظیمات') }}</flux:menu.item>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                    {{ __('خروج از سیستم') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<!-- Mobile User Menu -->
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />
    <flux:dropdown position="top" align="end">
        <flux:profile
            :initials="auth()->user()->initials()"
            icon-trailing="chevron-down"
        />
        <flux:menu>
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->full_fa_name() }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->user_name }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>
            <flux:menu.separator />

            <flux:menu.radio.group>
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('تنظیمات') }}</flux:menu.item>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                    {{ __('خروج از سیستم') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

{{ $slot }}

@fluxScripts
<flux:toast />
</body>
</html>
