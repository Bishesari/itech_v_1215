<?php

use App\Models\Province;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public Province $province;

    public string $name_fa = '';
    public string $name_en = '';


    public function mount(): void
    {
        $this->name_fa = $this->province->name_fa;
        $this->name_en = $this->province->name_en;
    }

    public function delete_province(): void
    {
        $this->province->delete();
        $this->modal('delete-province-' . $this->province->id)->close();
        $this->dispatch('province-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'استان جدید با موفقیت حذف شد.',
            variant: 'danger'
        );
    }

}; ?>

<div class="text-center">
    <flux:tooltip content="حذف استان" position="bottom">
        <flux:icon.trash variant="micro" class="cursor-pointer size-5 text-red-500 dark:text-red-400"
                         x-on:click="$flux.modal('delete-province-{{ $province->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="delete-province-{{ $province->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('حذف استان ')}} <span
                        class="font-bold text-red-500 dark:text-red-400">{{ $province->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('با تایید حذف اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <form wire:submit.prevent="delete_province" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="red" size="sm"
                                 class="cursor-pointer">{{__('تایید حذف')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
