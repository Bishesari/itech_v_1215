<?php

use App\Models\Standard;
use Flux\Flux;
use Livewire\Volt\Component;

new class extends Component {

    public Standard $standard;

    public string $name_fa = '';


    public function mount(): void
    {
        $this->name_fa = $this->standard->name_fa;
    }

    public function delete_model(): void
    {
        $this->standard->delete();
        $this->modal('delete-standard-' . $this->standard->id)->close();
        $this->dispatch('standard-deleted');

        Flux::toast(
            heading: 'حذف شد.',
            text: 'استاندارد با موفقیت حذف شد.',
            variant: 'danger'
        );
    }

}; ?>

<div class="text-center">
    <flux:tooltip content="حذف استاندارد" position="bottom">
        <flux:icon.trash variant="micro" class="cursor-pointer size-5 text-red-500 dark:text-red-400"
                         x-on:click="$flux.modal('delete-standard-{{ $standard->id }}').show()"/>
    </flux:tooltip>

    <flux:modal name="delete-standard-{{ $standard->id }}" :show="$errors->isNotEmpty()" focusable class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('حذف استاندارد ')}} <span
                        class="font-bold text-red-500 dark:text-red-400">{{ $standard->name_fa }}</span></flux:heading>
                <flux:text class="mt-2">{{__('با تایید حذف اطلاعات مربوطه حذف خواهند شد.')}}</flux:text>
            </div>

            <form wire:submit.prevent="delete_model" class="space-y-4 flex flex-col gap-3" autocomplete="off">
                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="red" size="sm"
                                 class="cursor-pointer">{{__('تایید حذف')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
