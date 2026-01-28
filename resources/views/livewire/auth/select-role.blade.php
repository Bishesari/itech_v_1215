<?php

use App\Models\BranchRoleUser;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.auth')]
#[Title('انتخاب نقش')]
class extends Component {

    public $bru_s = [];

    public ?int $selectedBruId = null;

    public ?int $selectedRoleId = null;
    public ?int $selectedBranchId = null;
    public ?string $selectedColor = '';

    public function mount(): void
    {
        $bru_s = auth()->user()->getAllRolesWithBranches();

        if ($bru_s->count() === 1) {
            $bru = $bru_s->first();

            session([
                'active_role_id' => $bru->role_id,
                'active_branch_id' => $bru->branch_id,
                'color' => $bru->role_color
            ]);

            $this->redirectIntended('dashboard', navigate: true);
            return;
        }
        $this->bru_s = $bru_s;
    }

    public function setBru($bru_id, $rc): void
    {
        $bru = BranchRoleUser::find($bru_id);
        $this->selectedBruId = $bru_id;
        $this->selectedRoleId = $bru->role_id;
        $this->selectedBranchId = $bru->branch_id;
        $this->selectedColor = $rc;
    }


    public function dashboard(): void
    {
        if (empty($this->selectedBruId)) {
            $this->addError('selectedRoleId', 'لطفاً یک نقش انتخاب کنید.');
            return;
        }
        session([
            'active_role_id' => $this->selectedRoleId,
            'active_branch_id' => $this->selectedBranchId ?? '',
            'color' => $this->selectedColor
        ]);
        // ✅ همه‌چیز اوکیه، هدایت به داشبورد
        $this->redirectIntended('dashboard', navigate: true);
    }

}; ?>
<div class="flex flex-col gap-3 pb-4">
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
    @forelse($bru_s as $bru)
        <flux:callout
            wire:click="setBru({{ $bru->id }}, '{{$bru->role_color}}')"
            color="{{ $selectedBruId == $bru->id ?  $bru->role_color  : 'zinc' }}" class="cursor-pointer">
            <flux:callout.heading class="flex justify-between">
                <span>{{ $bru->role_name }}</span>
                @if($bru->branch_name)
                    <span class="text-xs font-light">{{__('شعبه : ')}} {{ $bru->branch_name }}</span>
                @endif
            </flux:callout.heading>
        </flux:callout>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400">شما هیچ نقشی ندارید.</p>
        @endforelse


    <!-- Error -->
    @error('selectedRoleId')
    <p class="text-red-500 text-sm text-center">{{ $message }}</p>
    @enderror

    @if($selectedBruId)
        <!-- CTA Button -->
        <flux:button wire:loading.remove wire:target="setBru" wire:click="dashboard" variant="primary" color="indigo"
                     class="cursor-pointer w-full py-2 text-sm font-medium relative" x-data="{ loading: false }"
                     @click="loading = true">
            <span x-show="!loading">{{ __('ادامه با نقش انتخابی') }}</span>
            <flux:icon.loading x-show="loading" class="size-5"/>
        </flux:button>

        <flux:button wire:loading wire:target="setBru"
                     class="w-full py-2 text-sm text-center font-medium">
            <flux:icon.loading class="inline-block"/>
        </flux:button>

    @endif

</div>
