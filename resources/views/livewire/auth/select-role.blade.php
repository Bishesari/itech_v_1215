<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.auth')]
#[Title('انتخاب نقش')]
class extends Component {
    public $roles = [];

    public ?int $selectedRoleId = null;
    public ?int $selectedBranchId = null;

    public function mount(): void
    {
        $roles = auth()->user()->getAllRolesWithBranches();

        if ($roles->count() === 1) {
            $role = $roles->first();

            session([
                'active_role_id'   => $role->role_id,
                'active_branch_id' => $role->branch_id,
            ]);

            $this->redirectIntended('dashboard', navigate: true);
            return;
        }
        $this->roles = $roles;
    }

    public function setRole($roleId, $branch_id): void
    {
        $this->selectedRoleId = $roleId;
        $this->selectedBranchId = $branch_id;
    }


    public function dashboard(): void
    {
        if (empty($this->selectedRoleId)) {
            $this->addError('selectedRoleId', 'لطفاً یک نقش انتخاب کنید.');
            return;
        }
        session([
            'active_role_id'   => $this->selectedRoleId,
            'active_branch_id' => $this->selectedBranchId ?? '',
        ]);
        // ✅ همه‌چیز اوکیه، هدایت به داشبورد
        $this->redirectIntended('dashboard', navigate: true);
    }

}; ?>
<div class="flex flex-col gap-6 pb-4">

    <!-- Header -->
    <div class="text-center space-y-2">
        <h1 class="text-xl text-gray-800 dark:text-gray-200 font-bold">
            {{ __('انتخاب نقش کاربری') }}
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">
            {{ __('برای ورود، یکی از نقش‌های زیر را انتخاب کنید') }}
        </p>
    </div>

    <!-- Roles -->
    <flux:radio.group variant="cards" class="grid gap-4">
        @forelse($roles as $r)
            <flux:radio
                wire:click="setRole({{ $r->role_id }}, {{ $r->branch_id ?? 'null' }})"
                class="cursor-pointer {{ $selectedRoleId == $r->role_id ? 'text-green-600 dark:text-green-500' : 'text-gray-600 dark:text-gray-400' }} "
            >
                <div class="flex items-center justify-between w-full">
                    <!-- نقش (سمت راست) -->
                    <span class="font-semibold">
                        {{ $r->role_name }}
                    </span>

                    <!-- شعبه (سمت چپ) -->
                    @if($r->branch_name)
                        <span class="text-sm">
                           {{__('شعبه ')}} {{ $r->branch_name }}
                        </span>
                    @endif
                </div>
            </flux:radio>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400">شما هیچ نقشی ندارید.</p>
        @endforelse
    </flux:radio.group>

    <!-- Error -->
    @error('selectedRoleId')
    <p class="text-red-500 text-sm text-center">{{ $message }}</p>
    @enderror

    @if($selectedRoleId)
        <!-- CTA Button -->
        <flux:button wire:loading.remove wire:target="setRole" wire:click="dashboard" variant="primary" color="indigo"
                     class="cursor-pointer w-full py-2 text-sm font-medium relative" x-data="{ loading: false }" @click="loading = true">
            <span x-show="!loading">{{ __('ادامه با نقش انتخابی') }}</span>
            <flux:icon.loading x-show="loading" class="inline mr-2 size-5"/>
        </flux:button>

        <flux:button wire:loading wire:target="setRole"
                     class="w-full py-2 text-sm text-center font-medium">
            <flux:icon.loading class="inline-block"/>
        </flux:button>


    @endif

</div>
